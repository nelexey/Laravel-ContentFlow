export function initNotifications() {
    const notificationContainer = document.getElementById('notification-container');

    if (!notificationContainer) return;

    function showNotification(title, body, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type} transform transition-all duration-300 ease-in-out`;

        notification.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    ${type === 'article' ? `
                        <svg class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    ` : `
                        <svg class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    `}
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium text-gray-900">${title}</p>
                    <p class="mt-1 text-sm text-gray-500">${body}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="this.closest('.notification').remove()" class="inline-flex text-gray-400 hover:text-gray-500">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        `;

        notificationContainer.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateY(0)';
        }, 10);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-1rem)';
            setTimeout(() => notification.remove(), 300);
        }, 8000);
    }

    window.Echo.channel('articles')
        .listen('ArticleCreated', (e) => {
            showNotification(
                'Новая статья!',
                `${e.author} опубликовал(а): "${e.title}"`,
                'article'
            );
        });

    window.Echo.channel('comments')
        .listen('CommentCreated', (e) => {
            showNotification(
                'Новый комментарий!',
                `${e.user} прокомментировал(а) статью "${e.article_title}"`,
                'comment'
            );
        });
}
