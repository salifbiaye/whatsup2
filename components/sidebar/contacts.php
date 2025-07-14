<?php // Variables attendues : $contacts ?>
<div class="flex-1 contacts-list overflow-y-auto bg-white dark:bg-gray-900">
    <?php if (empty($contacts)): ?>
        <div class="flex flex-col items-center justify-center h-full px-12 text-center">
            <div class="w-20 h-20 mb-6 rounded-full bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/30 dark:to-amber-800/30 flex items-center justify-center">
                <svg class="w-10 h-10 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Aucun contact pour le moment</h3>
            <p class="text-gray-500 dark:text-gray-400 text-sm max-w-sm">Commencez à ajouter des contacts pour démarrer vos conversations</p>
        </div>
    <?php else: ?>
        <div class="p-2">
            <div class="mb-6">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    Contacts (<?php echo count($contacts); ?>)
                </h2>
            </div>
            
            <ul class="space-y-1 contacts-list">
                <?php foreach ($contacts as $contact): ?>
                    <li class="group">
                        <a href="/whatsup/chat_private?user=<?php echo urlencode($contact['id']); ?>" 
                           class="flex items-center gap-3 p-1 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 hover:shadow-sm transition-all duration-200 ease-in-out transform hover:scale-[1.01] border border-transparent hover:border-amber-100 dark:hover:border-gray-700">
                            
                            <!-- Avatar avec indicateur de statut -->
                            <div class="relative flex-shrink-0">
                                <img src="/whatsup/<?php echo $contact['avatar'] ?: 'storage/avatars/avatar_default.png'; ?>" 
                                     alt="<?php echo htmlspecialchars($contact['displayName']); ?>" 
                                     class="w-12 h-12 rounded-full object-cover border-2 border-white dark:border-gray-700 shadow-sm">
                                
                                <!-- Indicateur de statut -->
                                <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full border-2 border-white dark:border-gray-800 
                                          <?php echo $contact['status'] === 'online' ? 'bg-green-500' : 'bg-gray-400'; ?>
                                          <?php echo $contact['status'] === 'online' ? 'shadow-lg shadow-green-500/50' : ''; ?>">
                                </div>
                            </div>
                            
                            <!-- Informations du contact -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-medium text-gray-900 dark:text-gray-100 truncate text-sm">
                                        <?php echo htmlspecialchars($contact['displayName']); ?>
                                    </h3>
                                    
                                    <!-- Badge de statut -->
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                               <?php echo $contact['status'] === 'online' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400'; ?>">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1.5 
                                                   <?php echo $contact['status'] === 'online' ? 'bg-green-500' : 'bg-gray-400'; ?>"></span>
                                        <?php echo $contact['status'] === 'online' ? 'En ligne' : 'Hors ligne'; ?>
                                    </span>
                                </div>
                                
                                <!-- Dernière activité (optionnel) -->
                                <?php if (isset($contact['lastActivity'])): ?>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Dernière activité: <?php echo $contact['lastActivity']; ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Flèche d'action -->
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<style>
.contacts-list a {
    position: relative;
    overflow: hidden;
}

.contacts-list a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.contacts-list a:hover::before {
    left: 100%;
}

/* Animation pour les avatars */
.contacts-list img {
    transition: transform 0.2s ease-in-out;
}

.contacts-list a:hover img {
    transform: scale(1.1);
}

/* Effet de pulsation pour les utilisateurs en ligne */
@keyframes pulse-green {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.contacts-list [class*="bg-green-500"] {
    animation: pulse-green 2s infinite;
}

/* Scrollbar personnalisée */
.contacts-list::-webkit-scrollbar {
    width: 4px;
}

.contacts-list::-webkit-scrollbar-track {
    background: transparent;
}

.contacts-list::-webkit-scrollbar-thumb {
    background: rgba(156, 163, 175, 0.3);
    border-radius: 2px;
}

.contacts-list::-webkit-scrollbar-thumb:hover {
    background: rgba(156, 163, 175, 0.5);
}
</style>