/**
 * Accessibility Improvements - Auto add aria-label to links
 * Tự động thêm aria-label cho các thẻ <a> thiếu attribute
 */
(function () {
    'use strict';

    /**
     * Lấy text mô tả có ý nghĩa từ element
     */
    function getDescriptiveText(link) {
        if (link.getAttribute('aria-label')) {
            return null;
        }

        let text = '';
        const classList = link.classList;
        const textContent = link.textContent.trim();

        if (classList.contains('auth')) {
            var authorName = '';

            var parent = link.parentElement;
            if (parent) {
                var authorLink = parent.querySelector('a.ms-1');
                if (authorLink) {
                    authorName = authorLink.textContent.trim();
                }
            }

            if (link.querySelector('img')) {
                text = authorName ?
                    'View ' + authorName + ' profile' :
                    'View author profile';
            } else {
                text = authorName ?
                    authorName + ' profile' :
                    'View author profile';
            }
            return text;
        }

        if (classList.contains('bs-author-pic')) {
            var authorNameElem = null;
            var container = link.closest('.bs-info-author-block');
            if (container) {
                authorNameElem = container.querySelector('.title a');
                if (!authorNameElem) {
                    authorNameElem = container.querySelector('h4 a');
                }
            }

            if (authorNameElem) {
                text = 'View ' + authorNameElem.textContent.trim() + ' profile';
            } else {
                text = 'View author profile and posts';
            }
            return text;
        }

        if (classList.contains('link-div')) {
            var postContainer = link.closest('.bs-blog-post');
            if (postContainer) {
                var titleElem = postContainer.querySelector('.title');
                if (!titleElem) {
                    titleElem = postContainer.querySelector('h4.title');
                }
                if (!titleElem) {
                    titleElem = postContainer.querySelector('h3.title');
                }
                if (!titleElem) {
                    titleElem = postContainer.querySelector('.title a');
                }

                if (titleElem) {
                    var postTitle = titleElem.textContent.trim();
                    text = 'Read more: ' + postTitle;
                    return text;
                }
            }
            text = 'Read full article';
            return text;
        }

        if (classList.contains('bs-blog-thumb')) {
            var postContainer2 = link.closest('.bs-blog-post');
            if (postContainer2) {
                var titleElem2 = postContainer2.querySelector('.title');
                if (titleElem2) {
                    text = 'Featured image for: ' + titleElem2.textContent.trim();
                    return text;
                }
            }
            text = 'View featured image';
            return text;
        }

        if (textContent) {
            text = textContent;
        }

        var img = link.querySelector('img');
        if (img) {
            var altText = img.getAttribute('alt');
            if (altText) {
                text = text ? text + ' - ' + altText : altText;
            }
        }

        var tagParent = link.closest('.tag-links');
        if (tagParent) {
            text = text || 'View posts tagged with ' + textContent;
            return text;
        }

        var catParent = link.closest('.bs-blog-category');
        if (catParent) {
            text = text || 'View posts in category ' + textContent;
            return text;
        }

        if (!text) {
            var title = link.getAttribute('title');
            if (title) {
                text = title;
            }
        }

        if (!text) {
            var href = link.getAttribute('href');
            if (href && href !== '#' && href !== '') {
                var urlParts = href.split('/').filter(Boolean);
                var lastPart = urlParts[urlParts.length - 1];
                if (lastPart) {
                    text = 'Link to ' + lastPart.replace(/-/g, ' ');
                }
            }
        }

        return text || null;
    }

    /**
     * Thêm aria-label cho tất cả các link thiếu aria-label
     */
    function addAriaLabelsToLinks() {
        const links = document.querySelectorAll('a:not([aria-label])');

        let count = 0;
        links.forEach(function (link) {
            const ariaLabel = getDescriptiveText(link);

            if (ariaLabel) {
                link.setAttribute('aria-label', ariaLabel);
                count++;
            }
        });

        if (count > 0 && window.console) {
            console.log(`✓ Accessibility: Added aria-label to ${count} links`);
        }
    }

    /**
     * Chạy khi DOM đã load xong
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', addAriaLabelsToLinks);
    } else {
        addAriaLabelsToLinks();
    }

    /**
     * Observe cho dynamic content (AJAX loaded)
     */
    if (window.MutationObserver) {
        const observer = new MutationObserver(function (mutations) {
            let shouldUpdate = false;

            mutations.forEach(function (mutation) {
                if (mutation.addedNodes.length > 0) {
                    mutation.addedNodes.forEach(function (node) {
                        if (node.nodeType === 1 && (node.tagName === 'A' || node.querySelector('a'))) {
                            shouldUpdate = true;
                        }
                    });
                }
            });

            if (shouldUpdate) {
                addAriaLabelsToLinks();
            }
        });

        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    }

})();

