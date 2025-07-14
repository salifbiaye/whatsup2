<?php
// Variables attendues : $groups
?>
<?php if (!empty($groups)): ?>
    <div class="mt-6 border-t  overflow-x-hidden border-gray-200 dark:border-gray-700 pt-6">
        <div class="px-1 mb-3">
            <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                Groupes (<?php echo count($groups); ?>)
            </h2>
        </div>
        
        <ul class="space-y-1 overflow-y-auto h-40 p-2 groups-list">
            <?php foreach ($groups as $group): ?>
                <li class="group">
                    <a href="/whatsup/chat_group?group=<?php echo urlencode($group['id']); ?>" 
                       class="flex items-center gap-3 p-1 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 hover:shadow-sm transition-all duration-200 ease-in-out transform hover:scale-[1.01] border border-transparent hover:border-amber-100 dark:hover:border-gray-700">
                        
                        <!-- Icône de groupe -->
                        <div class="relative flex-shrink-0">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-amber-500 to-amber-600 dark:from-amber-600 dark:to-amber-700 flex items-center justify-center shadow-sm">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            
                            <!-- Indicateur d'activité -->
                            <?php if (isset($group['hasUnread']) && $group['hasUnread']): ?>
                                <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-red-500 border-2 border-white dark:border-gray-900 animate-pulse"></div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Informations du groupe -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100 truncate text-sm">
                                    <?php echo htmlspecialchars($group['name']); ?>
                                </h3>
                            </div>
                            
                            <!-- Nombre de membres -->
                            <div class="flex items-center gap-2 mt-0.5">
                                <svg class="w-3 h-3 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                </svg>
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    <?php echo count($group['members']); ?> membre<?php echo count($group['members']) > 1 ? 's' : ''; ?>
                                </span>
                            </div>
                        </div>
                        
                        <!-- Indicateur de navigation -->
                        <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-3.582 8-8 8a8.959 8.959 0 01-4.906-1.478l-3.178 1.043 1.043-3.178A8.959 8.959 0 013 12c0-4.418 3.582-8 8-8s8 3.582 8 8z" />
                            </svg>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<style>
.groups-list a {
    position: relative;
    overflow: hidden;
}

.groups-list a::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 126, 20, 0.1), transparent);
    transition: left 0.5s;
}

.groups-list a:hover::before {
    left: 100%;
}

/* Animation pour les icônes de groupe */
.groups-list .group:hover div[class*="bg-gradient-to-br"] {
    transform: scale(1.05);
    transition: transform 0.2s ease-in-out;
}

/* Effet de rotation pour l'icône au hover */
.groups-list .group:hover svg:first-child {
    transform: rotate(10deg);
    transition: transform 0.2s ease-in-out;
}

/* Animation spéciale pour les groupes avec notifications */
@keyframes pulse-red {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.groups-list [class*="bg-red-500"] {
    animation: pulse-red 1.5s infinite;
}

/* Hover effect pour les couleurs orange */
.groups-list a:hover {
    background: rgba(255, 126, 20, 0.1);
}

/* Scrollbar personnalisée pour les groupes */
.groups-list::-webkit-scrollbar {
    width: 4px;
}

.groups-list::-webkit-scrollbar-track {
    background: transparent;
}

.groups-list::-webkit-scrollbar-thumb {
    background: rgba(255, 126, 20, 0.3);
    border-radius: 2px;
}

.groups-list::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 126, 20, 0.5);
}
</style>