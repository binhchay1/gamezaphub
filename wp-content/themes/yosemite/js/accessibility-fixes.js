/**
 * Accessibility Fixes JavaScript for PageSpeed Insights
 * 
 * @package Yosemite
 * @version 1.3.1
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        
        // Fix Search Button Accessibility
        const searchButton = document.querySelector('.header-search .fa-search');
        if (searchButton) {
            searchButton.setAttribute('aria-label', 'Open search');
            searchButton.setAttribute('role', 'button');
            searchButton.setAttribute('tabindex', '0');
            
            // Add keyboard support
            searchButton.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    searchButton.click();
                }
            });
        }
        
        // Fix Mobile Menu Button Accessibility
        const mobileMenuButton = document.querySelector('.toggle-mobile-menu');
        if (mobileMenuButton) {
            mobileMenuButton.setAttribute('aria-label', 'Toggle mobile menu');
            mobileMenuButton.setAttribute('aria-expanded', 'false');
            mobileMenuButton.setAttribute('role', 'button');
            
            // Add keyboard support
            mobileMenuButton.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    mobileMenuButton.click();
                }
            });
            
            // Update aria-expanded when menu is toggled
            mobileMenuButton.addEventListener('click', function() {
                const isExpanded = mobileMenuButton.getAttribute('aria-expanded') === 'true';
                mobileMenuButton.setAttribute('aria-expanded', !isExpanded);
            });
        }
        
        // Fix Links Without Discernible Text
        const links = document.querySelectorAll('a');
        links.forEach(function(link) {
            const text = link.textContent.trim();
            const hasAriaLabel = link.hasAttribute('aria-label');
            const hasTitle = link.hasAttribute('title');
            
            // If link has no text and no aria-label, add one
            if (!text && !hasAriaLabel && !hasTitle) {
                const href = link.getAttribute('href');
                if (href === '#') {
                    link.setAttribute('aria-label', 'Link');
                } else if (href) {
                    // Extract meaningful text from URL
                    const urlParts = href.split('/');
                    const lastPart = urlParts[urlParts.length - 1];
                    if (lastPart && lastPart !== '') {
                        link.setAttribute('aria-label', 'Link to ' + lastPart.replace(/[-_]/g, ' '));
                    } else {
                        link.setAttribute('aria-label', 'Link to ' + href);
                    }
                }
            }
            
            // Add focus styles
            link.addEventListener('focus', function() {
                this.style.outline = '2px solid #0073aa';
                this.style.outlineOffset = '2px';
                this.style.backgroundColor = '#e8f4fd';
            });
            
            link.addEventListener('blur', function() {
                this.style.outline = '';
                this.style.outlineOffset = '';
                this.style.backgroundColor = '';
            });
        });
        
        // Fix Heading Order
        const headings = document.querySelectorAll('h1, h2, h3, h4, h5, h6');
        let lastLevel = 0;
        let headingStack = [];
        
        headings.forEach(function(heading) {
            const level = parseInt(heading.tagName.charAt(1));
            
            // Update heading stack
            while (headingStack.length > 0 && headingStack[headingStack.length - 1] >= level) {
                headingStack.pop();
            }
            headingStack.push(level);
            
            // Check for skipped levels
            if (level > lastLevel + 1 && lastLevel > 0) {
                console.warn('Heading level skipped: ' + heading.tagName + ' after h' + lastLevel);
                
                // Optionally fix by changing the heading level
                const newLevel = Math.min(lastLevel + 1, 6);
                const newTag = 'h' + newLevel;
                
                const newHeading = document.createElement(newTag);
                newHeading.innerHTML = heading.innerHTML;
                newHeading.className = heading.className;
                newHeading.id = heading.id;
                
                // Copy all attributes
                Array.from(heading.attributes).forEach(function(attr) {
                    newHeading.setAttribute(attr.name, attr.value);
                });
                
                heading.parentNode.replaceChild(newHeading, heading);
                lastLevel = newLevel;
            } else {
                lastLevel = level;
            }
        });
        
        // Add Skip Links
        const skipLinks = document.createElement('div');
        skipLinks.innerHTML = `
            <a href="#main" class="skip-link screen-reader-text">Skip to main content</a>
            <a href="#navigation" class="skip-link screen-reader-text">Skip to navigation</a>
            <a href="#sidebar" class="skip-link screen-reader-text">Skip to sidebar</a>
        `;
        document.body.insertBefore(skipLinks, document.body.firstChild);
        
        // Fix Form Accessibility
        const forms = document.querySelectorAll('form');
        forms.forEach(function(form) {
            const inputs = form.querySelectorAll('input, textarea, select');
            
            inputs.forEach(function(input) {
                // Add proper labels if missing
                if (!input.getAttribute('aria-label') && !input.getAttribute('aria-labelledby')) {
                    const placeholder = input.getAttribute('placeholder');
                    if (placeholder) {
                        input.setAttribute('aria-label', placeholder);
                    }
                }
                
                // Add focus styles
                input.addEventListener('focus', function() {
                    this.style.outline = '2px solid #0073aa';
                    this.style.outlineOffset = '2px';
                    this.style.borderColor = '#0073aa';
                });
                
                input.addEventListener('blur', function() {
                    this.style.outline = '';
                    this.style.outlineOffset = '';
                    this.style.borderColor = '';
                });
            });
        });
        
        // Fix Table Accessibility
        const tables = document.querySelectorAll('table');
        tables.forEach(function(table) {
            // Add caption if missing
            if (!table.querySelector('caption')) {
                const caption = document.createElement('caption');
                caption.textContent = 'Table data';
                table.insertBefore(caption, table.firstChild);
            }
            
            // Add role if missing
            if (!table.getAttribute('role')) {
                table.setAttribute('role', 'table');
            }
            
            // Add headers to cells
            const headers = table.querySelectorAll('th');
            const cells = table.querySelectorAll('td');
            
            headers.forEach(function(header, index) {
                header.setAttribute('scope', 'col');
            });
            
            // Add row headers
            const rows = table.querySelectorAll('tr');
            rows.forEach(function(row, rowIndex) {
                const firstCell = row.querySelector('td, th');
                if (firstCell && firstCell.tagName === 'TH') {
                    firstCell.setAttribute('scope', 'row');
                }
            });
        });
        
        // Add Live Region for Dynamic Content
        const liveRegion = document.createElement('div');
        liveRegion.setAttribute('aria-live', 'polite');
        liveRegion.setAttribute('aria-atomic', 'true');
        liveRegion.className = 'screen-reader-text';
        liveRegion.id = 'live-region';
        document.body.appendChild(liveRegion);
        
        // Announce page changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                    liveRegion.textContent = 'Content updated';
                    setTimeout(function() {
                        liveRegion.textContent = '';
                    }, 1000);
                }
            });
        });
        
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
        
        // Fix Image Alt Text
        const images = document.querySelectorAll('img');
        images.forEach(function(img) {
            if (!img.getAttribute('alt')) {
                const src = img.getAttribute('src');
                if (src) {
                    const filename = src.split('/').pop().split('.')[0];
                    img.setAttribute('alt', filename.replace(/[-_]/g, ' '));
                }
            }
        });
        
        // Add Keyboard Navigation for Custom Elements
        const customButtons = document.querySelectorAll('.custom-gallery-container .thumbnail-image, .video-player button');
        customButtons.forEach(function(button) {
            button.setAttribute('tabindex', '0');
            button.setAttribute('role', 'button');
            
            button.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    button.click();
                }
            });
        });
        
        // Fix Color Contrast for Dynamic Content
        const style = document.createElement('style');
        style.textContent = `
            /* Ensure all dynamic content has proper contrast */
            .dynamic-content {
                color: #333 !important;
                background: #fff !important;
            }
            
            .dynamic-content a {
                color: #0073aa !important;
                text-decoration: underline !important;
            }
            
            .dynamic-content a:hover {
                color: #005177 !important;
                background-color: #e8f4fd !important;
            }
        `;
        document.head.appendChild(style);
        
        console.log('Accessibility fixes applied successfully');
    });

})();
