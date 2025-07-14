<?php
session_start();
if (!isset($_SESSION['email_id'])) {
    http_response_code(403);
    exit('Non autorisé');
}

$email_id = $_SESSION['email_id'];
$contact_id = $_GET['user'] ?? '';

if (!$contact_id) {
    exit('Contact non spécifié');
}

$users = simplexml_load_file(__DIR__ . '/../../storage/xml/users.xml');
$private_chats = simplexml_load_file(__DIR__ . '/../../storage/xml/private_chats.xml');

// Trouver le contact
$contact = null;
foreach ($users->user as $u) {
    if ((string)$u['id'] === $contact_id) {
        $contact = $u;
        break;
    }
}

if (!$contact) {
    exit('Contact introuvable');
}

// Trouver le chat
$chat = null;
foreach ($private_chats->chat as $c) {
    if ((($c['user1'] == $email_id && $c['user2'] == $contact_id) || ($c['user1'] == $contact_id && $c['user2'] == $email_id))) {
        $chat = $c;
        break;
    }
}

header('Content-Type: text/html; charset=utf-8');

if (!$chat || !isset($chat->messages->message)) {
    echo '<div class="flex items-center justify-center h-full">
        <div class="text-center">
            <div class="w-16 h-16 mx-auto mb-4 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <div class="text-gray-500 dark:text-gray-400 text-sm">Aucun message pour le moment</div>
            <div class="text-gray-400 dark:text-gray-500 text-xs mt-1">Commencez la conversation</div>
        </div>
    </div>';
    exit();
}

// Convertir les messages en array pour utiliser la même structure
$messages = [];
foreach ($chat->messages->message as $msg) {
    $file_info = null;
    if (isset($msg->file)) {
        $file_info = [
            'name' => (string)$msg->file['name'],
            'path' => (string)$msg->file['path']
        ];
    }
    
    $messages[] = [
        'sender' => (string)$msg['sender'],
        'text' => (string)$msg->text,
        'timestamp' => (string)$msg['timestamp'],
        'file' => $file_info
    ];
}
?>

<div class="space-y-2 py-2">
    <?php foreach ($messages as $msg): ?>
        <div class="flex <?php echo $msg['sender'] === $_SESSION['email_id'] ? 'justify-end' : 'justify-start'; ?>">
            <div class="max-w-xs sm:max-w-md lg:max-w-lg xl:max-w-xl">
                <div class="relative group">
                    <div class="<?php echo $msg['sender'] === $_SESSION['email_id'] ? 'bg-amber-500 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100'; ?> px-3 py-2 rounded-lg shadow-sm">
                        <!-- Message content -->
                        <div class="text-sm leading-relaxed whitespace-pre-wrap break-words">
                            <?php echo nl2br(htmlspecialchars($msg['text'])); ?>
                        </div>
                        
                        <!-- File attachment -->
                        <?php if ($msg['file']): ?>
                            <div class="mt-2 p-2 <?php echo $msg['sender'] === $_SESSION['email_id'] ? 'bg-amber-600/30' : 'bg-gray-100 dark:bg-gray-700'; ?> rounded">
                                <a class="flex items-center gap-2 text-xs <?php echo $msg['sender'] === $_SESSION['email_id'] ? 'text-amber-100 hover:text-white' : 'text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-100'; ?> transition-colors" href="/whatsup/<?php echo htmlspecialchars(str_replace('storage/', '/storage/', $msg['file']['path'])); ?>" target="_blank">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 002.828 2.828l6.586-6.586a4 4 0 10-5.656-5.656l-6.586 6.586a6 6 0 108.486 8.486l6.586-6.586"></path>
                                    </svg>
                                    <span class="truncate"><?php echo htmlspecialchars($msg['file']['name']); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Message info -->
                        <div class="flex items-center justify-between mt-1">
                            <div class="text-xs <?php echo $msg['sender'] === $_SESSION['email_id'] ? 'text-amber-100' : 'text-gray-500 dark:text-gray-400'; ?>">
                                <?php echo htmlspecialchars(date('H:i', strtotime($msg['timestamp']))); ?>
                            </div>
                            <?php if ($msg['sender'] === $_SESSION['email_id']): ?>
                                <div class="ml-2">
                                    <svg class="w-4 h-4 text-amber-100" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Message tail -->
                    <div class="<?php echo $msg['sender'] === $_SESSION['email_id'] ? 'absolute -right-1 bottom-0' : 'absolute -left-1 bottom-0'; ?>">
                        <svg class="w-3 h-3 <?php echo $msg['sender'] === $_SESSION['email_id'] ? 'text-amber-500' : 'text-white dark:text-gray-800'; ?>" viewBox="0 0 12 12" fill="currentColor">
                            <?php if ($msg['sender'] === $_SESSION['email_id']): ?>
                                <path d="M0 0v12l12-12H0z"/>
                            <?php else: ?>
                                <path d="M12 0v12L0 0h12z"/>
                            <?php endif; ?>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>