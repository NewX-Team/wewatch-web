<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display the Direct Messaging Hub.
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        // 1. Fetch conversations list for sidebar
        $conversations = collect();

        // Official Admin Support Thread
        $hasAdminChat = $user->canMessageAdmin();
        $adminUnreadCount = 0;
        $lastAdminMsg = null;

        if ($user->isSuperAdmin()) {
            // For SuperAdmin: find all users who initiated admin support chats
            $userChatIds = Message::where('is_admin_chat', true)
                ->pluck('sender_id')
                ->merge(Message::where('is_admin_chat', true)->pluck('receiver_id'))
                ->filter(fn ($id) => $id && $id !== $user->id)
                ->unique();

            $adminThreads = User::whereIn('id', $userChatIds)->get();
        } else {
            // For Regular User / Creator
            $lastAdminMsg = Message::where('is_admin_chat', true)
                ->where(function ($q) use ($user) {
                    $q->where('sender_id', $user->id)
                        ->orWhere('receiver_id', $user->id);
                })
                ->latest()
                ->first();

            $adminUnreadCount = Message::where('is_admin_chat', true)
                ->where('receiver_id', $user->id)
                ->where('is_read', false)
                ->count();
        }

        // Subscribed creators for user or subscribers for creator
        $creatorsList = $user->subscribedCreators()->with('subscribers')->get();
        if ($user->isCreator()) {
            $subscribersList = $user->subscribers()->get();
            $creatorsList = $creatorsList->merge($subscribersList)->unique('id');
        }

        // 2. Active selected thread
        $activeType = $request->query('type', 'creator'); // 'admin' or 'creator'
        $activeCreatorId = $request->query('creator_id');
        $activeUserId = $request->query('user_id');

        $activeCreator = null;
        $activeUser = null;
        $activeMessages = collect();
        $canSendMessage = true;
        $lockReason = null;

        if ($activeType === 'admin') {
            if (! $user->canMessageAdmin()) {
                $canSendMessage = false;
                $lockReason = 'Pesan langsung ke Tim Support Admin hanya dapat diakses oleh Anggota VIP.';
            } else {
                if ($user->isSuperAdmin() && $activeUserId) {
                    $activeUser = User::find($activeUserId);
                    if ($activeUser) {
                        $activeMessages = Message::where('is_admin_chat', true)
                            ->where(function ($q) use ($activeUser) {
                                $q->where('sender_id', $activeUser->id)
                                    ->orWhere('receiver_id', $activeUser->id);
                            })
                            ->orderBy('created_at', 'asc')
                            ->get();

                        // Mark as read
                        Message::where('is_admin_chat', true)
                            ->where('sender_id', $activeUser->id)
                            ->where('is_read', false)
                            ->update(['is_read' => true]);
                    }
                } else {
                    // Regular user messaging Admin
                    $activeMessages = Message::where('is_admin_chat', true)
                        ->where(function ($q) use ($user) {
                            $q->where('sender_id', $user->id)
                                ->orWhere('receiver_id', $user->id);
                        })
                        ->orderBy('created_at', 'asc')
                        ->get();

                    // Mark as read
                    Message::where('is_admin_chat', true)
                        ->where('receiver_id', $user->id)
                        ->where('is_read', false)
                        ->update(['is_read' => true]);
                }
            }
        } elseif ($activeCreatorId) {
            $activeCreator = User::find($activeCreatorId);
            if ($activeCreator) {
                if (! $activeCreator->canReceiveDmFrom($user)) {
                    $canSendMessage = false;
                    $requiredTier = strtoupper($activeCreator->dm_access_tier ?: 'PRO');
                    $lockReason = "Kreator '{$activeCreator->name}' membatasi Direct Message khusus untuk pengguna berstatus {$requiredTier}.";
                }

                $activeMessages = Message::where('is_admin_chat', false)
                    ->where(function ($q) use ($user, $activeCreator) {
                        $q->where(function ($q1) use ($user, $activeCreator) {
                            $q1->where('sender_id', $user->id)->where('receiver_id', $activeCreator->id);
                        })->orWhere(function ($q2) use ($user, $activeCreator) {
                            $q2->where('sender_id', $activeCreator->id)->where('receiver_id', $user->id);
                        });
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();

                // Mark messages from creator as read
                Message::where('is_admin_chat', false)
                    ->where('sender_id', $activeCreator->id)
                    ->where('receiver_id', $user->id)
                    ->where('is_read', false)
                    ->update(['is_read' => true]);
            }
        }

        return view('messages.index', compact(
            'hasAdminChat',
            'lastAdminMsg',
            'adminUnreadCount',
            'adminThreads',
            'creatorsList',
            'activeType',
            'activeCreator',
            'activeUser',
            'activeMessages',
            'canSendMessage',
            'lockReason'
        ));
    }

    /**
     * Send a direct message.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
            'receiver_id' => ['nullable', 'exists:users,id'],
            'is_admin_chat' => ['nullable', 'boolean'],
        ]);

        $sender = Auth::user();
        $isAdminChat = $request->boolean('is_admin_chat');
        $receiverId = $validated['receiver_id'] ?? null;

        if ($isAdminChat) {
            // Validate Admin chat permissions
            if (! $sender->canMessageAdmin()) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Hanya pengguna VIP yang dapat mengirim pesan ke Tim Admin Support.',
                    ], 403);
                }

                return redirect()->back()->with('error', 'Hanya pengguna VIP yang dapat mengirim pesan ke Tim Admin Support.');
            }

            // If superadmin is replying to a specific user
            if ($sender->isSuperAdmin() && $receiverId) {
                $msg = Message::create([
                    'sender_id' => $sender->id,
                    'receiver_id' => $receiverId,
                    'is_admin_chat' => true,
                    'message' => $validated['message'],
                    'is_read' => false,
                ]);
            } else {
                // User messaging Admin
                $msg = Message::create([
                    'sender_id' => $sender->id,
                    'receiver_id' => null,
                    'is_admin_chat' => true,
                    'message' => $validated['message'],
                    'is_read' => false,
                ]);
            }
        } else {
            // User to Creator chat validation
            if (! $receiverId) {
                return redirect()->back()->with('error', 'Penerima pesan tidak valid.');
            }

            $receiver = User::findOrFail($receiverId);
            if (! $receiver->canReceiveDmFrom($sender)) {
                $requiredTier = strtoupper($receiver->dm_access_tier ?: 'PRO');
                $errorMsg = "Kreator '{$receiver->name}' membatasi pesan khusus pengguna berstatus {$requiredTier}.";

                if ($request->wantsJson()) {
                    return response()->json(['status' => 'error', 'message' => $errorMsg], 403);
                }

                return redirect()->back()->with('error', $errorMsg);
            }

            $msg = Message::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'is_admin_chat' => false,
                'message' => $validated['message'],
                'is_read' => false,
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message_data' => [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'receiver_id' => $msg->receiver_id,
                    'message' => $msg->message,
                    'is_admin_chat' => $msg->is_admin_chat,
                    'time' => $msg->created_at->format('H:i'),
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Pesan berhasil dikirim!');
    }
}
