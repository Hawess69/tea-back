@extends('layouts.admin')

@section('title', 'View Feed Post - Tea Admin')
@section('page-title', 'Feed Post Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex items-center">
        <a href="{{ route('admin.feed-posts.index') }}" 
           class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Feed Posts
        </a>
    </div>

    <!-- Post Details Card -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <!-- Post Header -->
            <div class="flex items-start justify-between mb-6">
                <div class="flex items-start space-x-4">
                    <!-- Post Image -->
                    <div class="flex-shrink-0">
                        @if($feedPost->image_url)
                            <img class="h-20 w-20 rounded-lg object-cover" src="{{ $feedPost->image_url }}" alt="Post image">
                        @else
                            <div class="h-20 w-20 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Post Info -->
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Feed Post #{{ $feedPost->id }}</h1>
                        <div class="flex items-center space-x-4 mt-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $feedPost->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                   ($feedPost->status === 'draft' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : 
                                   'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                {{ ucfirst($feedPost->status) }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $feedPost->created_at->format('M d, Y \a\t g:i A') }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex space-x-2">
                    <a href="{{ route('admin.feed-posts.edit', $feedPost) }}" 
                       class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    
                    @if($feedPost->status === 'published')
                        <button onclick="toggleHide({{ $feedPost->id }}, 'feed', '{{ $feedPost->status }}')" 
                                class="inline-flex items-center px-3 py-2 border border-red-300 dark:border-red-600 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                            </svg>
                            Hide
                        </button>
                    @elseif($feedPost->status === 'hidden')
                        <button onclick="toggleHide({{ $feedPost->id }}, 'feed', '{{ $feedPost->status }}')" 
                                class="inline-flex items-center px-3 py-2 border border-green-300 dark:border-green-600 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 dark:text-green-300 bg-white dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            Unhide
                        </button>
                    @elseif($feedPost->status === 'draft')
                        <button onclick="publishPost({{ $feedPost->id }}, 'feed')" 
                                class="inline-flex items-center px-3 py-2 border border-green-300 dark:border-green-600 shadow-sm text-sm leading-4 font-medium rounded-md text-green-700 dark:text-green-300 bg-white dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-green-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Publish
                        </button>
                    @endif
                    
                    <button type="button" onclick="confirmDelete({{ $feedPost->id }}, 'post')" 
                            class="inline-flex items-center px-3 py-2 border border-red-300 dark:border-red-600 shadow-sm text-sm leading-4 font-medium rounded-md text-red-700 dark:text-red-300 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </div>
            </div>

            <!-- Post Content -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Content</h3>
                <div class="prose max-w-none dark:prose-invert">
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $feedPost->content }}</p>
                </div>
            </div>

            <!-- Author Information -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Author</h3>
                <div class="flex items-center space-x-3">
                    @if($feedPost->user->avatar)
                        <img class="h-10 w-10 rounded-full object-cover" src="{{ $feedPost->user->avatar }}" alt="{{ $feedPost->user->name }}">
                    @else
                        <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                            <span class="text-sm font-medium text-white">{{ substr($feedPost->user->name, 0, 2) }}</span>
                        </div>
                    @endif
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $feedPost->user->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $feedPost->user->email }}</div>
                    </div>
                </div>
            </div>

            <!-- Engagement Stats -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Engagement</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $feedPost->upvotes_count ?? 0 }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Upvotes</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $feedPost->comments->count() }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Comments</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $feedPost->downvotes_count ?? 0 }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">Downvotes</div>
                    </div>
                </div>
            </div>

            <!-- Flags -->
            @if($feedPost->flags && $feedPost->flags->count() > 0)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-3">Flags ({{ $feedPost->flags->count() }})</h3>
                <div class="space-y-2">
                    @foreach($feedPost->flags as $flag)
                        <div class="flex items-center justify-between p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $flag->flag_type === 'red' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                       ($flag->flag_type === 'green' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                                       'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200') }}">
                                    {{ ucfirst($flag->flag_type) }}
                                </span>
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $flag->reason ?? 'No reason provided' }}</span>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $flag->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Comments Section -->
    @if($feedPost->comments && $feedPost->comments->count() > 0)
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Comments ({{ $feedPost->comments->count() }})</h3>
            <div class="space-y-4">
                @foreach($feedPost->comments as $comment)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4" id="comment-{{ $comment->id }}">
                        <div class="flex items-start space-x-3">
                            @if($comment->user->avatar)
                                <img class="h-8 w-8 rounded-full object-cover" src="{{ $comment->user->avatar }}" alt="{{ $comment->user->name }}">
                            @else
                                <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">
                                    <span class="text-xs font-medium text-white">{{ substr($comment->user->name, 0, 2) }}</span>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $comment->user->name }}</p>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="editComment({{ $comment->id }}, '{{ addslashes($comment->body) }}')" 
                                                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </button>
                                        <button onclick="deleteComment({{ $comment->id }})" 
                                                class="text-xs text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div id="comment-body-{{ $comment->id }}">
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $comment->body }}</p>
                                </div>
                                <div id="comment-edit-{{ $comment->id }}" class="hidden mt-2">
                                    <textarea id="edit-textarea-{{ $comment->id }}" 
                                              class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm"
                                              rows="3">{{ $comment->body }}</textarea>
                                    <div class="flex space-x-2 mt-2">
                                        <button onclick="saveComment({{ $comment->id }})" 
                                                class="px-3 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700">
                                            Save
                                        </button>
                                        <button onclick="cancelEdit({{ $comment->id }})" 
                                                class="px-3 py-1 bg-gray-600 text-white text-xs rounded hover:bg-gray-700">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

<script>
// Comment management functions
function editComment(commentId, originalBody) {
    // Hide the comment body and show edit form
    document.getElementById(`comment-body-${commentId}`).classList.add('hidden');
    document.getElementById(`comment-edit-${commentId}`).classList.remove('hidden');
    
    // Focus on the textarea
    document.getElementById(`edit-textarea-${commentId}`).focus();
}

function cancelEdit(commentId) {
    // Show the comment body and hide edit form
    document.getElementById(`comment-body-${commentId}`).classList.remove('hidden');
    document.getElementById(`comment-edit-${commentId}`).classList.add('hidden');
}

async function saveComment(commentId) {
    const textarea = document.getElementById(`edit-textarea-${commentId}`);
    const newBody = textarea.value.trim();
    
    if (!newBody) {
        alert('Comment cannot be empty');
        return;
    }
    
    if (newBody.length > 1000) {
        alert('Comment cannot exceed 1000 characters');
        return;
    }
    
    // Check if CSRF token exists
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        alert('CSRF token not found. Please refresh the page and try again.');
        return;
    }
    
    const tokenValue = csrfToken.getAttribute('content');
    if (!tokenValue) {
        alert('CSRF token is empty. Please refresh the page and try again.');
        return;
    }
    
    try {
        const response = await fetch(`/admin/comments/${commentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': tokenValue
            },
            body: JSON.stringify({ body: newBody })
        });
        
        if (response.ok) {
            // Update the comment body display
            document.querySelector(`#comment-body-${commentId} p`).textContent = newBody;
            cancelEdit(commentId);
            
            // Show success message
            showNotification('Comment updated successfully', 'success');
        } else {
            const error = await response.json();
            if (response.status === 419) {
                // CSRF token mismatch
                if (confirm('Session expired. Would you like to refresh the page?')) {
                    refreshPage();
                } else {
                    showNotification('Session expired. Please refresh the page and try again.', 'error');
                }
            } else if (response.status === 404) {
                // Comment was deleted or doesn't exist
                showNotification('Comment not found - it may have been deleted', 'error');
                // Remove the comment from the DOM
                const commentElement = document.getElementById(`comment-${commentId}`);
                if (commentElement) {
                    commentElement.remove();
                }
            } else {
                alert('Failed to update comment: ' + (error.message || 'Unknown error'));
            }
        }
    } catch (error) {
        console.error('Error updating comment:', error);
        alert('Failed to update comment. Please try again.');
    }
}

async function deleteComment(commentId) {
    if (!confirm('Are you sure you want to delete this comment? This action cannot be undone.')) {
        return;
    }
    
    try {
        const response = await fetch(`/admin/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            // Remove the comment from the DOM
            document.getElementById(`comment-${commentId}`).remove();
            
            // Update comment count
            const commentCountElement = document.querySelector('h3');
            if (commentCountElement) {
                const match = commentCountElement.textContent.match(/\((\d+)\)/);
                if (match && match[1]) {
                    const currentCount = parseInt(match[1]);
                    commentCountElement.textContent = `Comments (${currentCount - 1})`;
                }
            }
            
            // Show success message
            showNotification('Comment deleted successfully', 'success');
        } else {
            const error = await response.json();
            alert('Failed to delete comment: ' + (error.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error deleting comment:', error);
        alert('Failed to delete comment. Please try again.');
    }
}

// Simple notification function
function refreshPage() {
    window.location.reload();
}

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500 text-white' : 
        type === 'error' ? 'bg-red-500 text-white' : 
        'bg-blue-500 text-white'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}
</script>
@endsection
