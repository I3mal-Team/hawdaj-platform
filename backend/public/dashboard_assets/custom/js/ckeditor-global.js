/**
 * Global CKEditor Initialization
 * Automatically initializes CKEditor on any textarea with class 'description' or 'description-seo'
 */
(function() {
    'use strict';

    // Wait for CKEditor library to be loaded
    function initCKEditors() {
        if (typeof ClassicEditor === 'undefined') {
            // CKEditor not loaded yet, try again after a short delay
            setTimeout(initCKEditors, 100);
            return;
        }

        // Configuration for CKEditor
        const editorConfig = {
            toolbar: {
                items: [
                    'heading', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'fontSize', 'fontColor', 'fontBackgroundColor', 'fontFamily', '|',
                    'bulletedList', 'numberedList', '|',
                    'alignment', '|',
                    'link', 'blockQuote', '|',
                    'undo', 'redo'
                ],
                shouldNotGroupWhenFull: true
            },
            removePlugins: [
                'Image', 
                'ImageUpload', 
                'ImageCaption', 
                'ImageStyle', 
                'ImageToolbar', 
                'ImageResize', 
                'MediaEmbed', 
                'Table', 
                'TableToolbar'
            ]
        };

        // Initialize editors on all textareas with class 'description' or 'description-seo'
        const editors = document.querySelectorAll('.description, .description-seo');
        
        editors.forEach(function(textarea) {
            // Skip if already initialized
            if (textarea.dataset.ckeditorInitialized === 'true') {
                return;
            }

            ClassicEditor
                .create(textarea, editorConfig)
                .then(function(editor) {
                    textarea.dataset.ckeditorInitialized = 'true';
                })
                .catch(function(error) {
                    console.error('Error initializing CKEditor:', error);
                });
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Load CKEditor script if not already loaded
            if (typeof ClassicEditor === 'undefined') {
                var script = document.createElement('script');
                script.src = 'https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js';
                script.onload = initCKEditors;
                document.head.appendChild(script);
            } else {
                initCKEditors();
            }
        });
    } else {
        // DOM already loaded
        if (typeof ClassicEditor === 'undefined') {
            var script = document.createElement('script');
            script.src = 'https://cdn.ckeditor.com/ckeditor5/23.0.0/classic/ckeditor.js';
            script.onload = initCKEditors;
            document.head.appendChild(script);
        } else {
            initCKEditors();
        }
    }

    // Re-initialize editors when new content is loaded dynamically (e.g., tabs, AJAX)
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.addedNodes.length) {
                initCKEditors();
            }
        });
    });

    // Start observing
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
})();

