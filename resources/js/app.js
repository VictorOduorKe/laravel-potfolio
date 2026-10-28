/**
 * TheTechCraft Portfolio Interactive Client Logic
 */

function onReady(fn) {
    if (document.readyState !== 'loading') {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

onReady(() => {
    initScrollReveal();
    initProjectShowcase();
    initAnimatedCounters();
    initMobileNav();
    initChatAssistant();
    initContactForm();
    initToastAutoDismiss();
});

// Enable smooth scrolling after initial page load & scroll restoration settle
window.addEventListener('load', () => {
    requestAnimationFrame(() => {
        document.documentElement.classList.add('smooth-scroll');
    });
});

// Handle Back/Forward Cache (BFCache) navigation & window visibility
window.addEventListener('pageshow', (event) => {
    // Reveal all elements immediately on cache restore
    document.querySelectorAll('.reveal').forEach((el) => {
        el.classList.add('active');
        el.classList.remove('reveal-pending');
    });
});

/* 1. Scroll Reveal Animations with Progressive Enhancement & Accessibility */
function initScrollReveal() {
    const reveals = document.querySelectorAll('.reveal');
    if (!reveals.length) return;

    // Immediately activate if reduced-motion preferred or IntersectionObserver unsupported
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
        reveals.forEach((el) => {
            el.classList.add('active');
            el.classList.remove('reveal-pending');
        });
        return;
    }

    const vHeight = window.innerHeight || document.documentElement.clientHeight;

    // Keep all elements already in or above viewport active so they NEVER disappear or flash
    reveals.forEach((el) => {
        const rect = el.getBoundingClientRect();
        if (rect.top < vHeight - 30) {
            el.classList.add('active');
        } else {
            // Only hide elements strictly below current viewport for scroll animation
            el.classList.add('reveal-pending');
        }
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                entry.target.classList.remove('reveal-pending');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.05,
        rootMargin: '0px 0px -20px 0px'
    });

    reveals.forEach((el) => {
        if (!el.classList.contains('active')) {
            observer.observe(el);
        }
    });

    // Failsafe timeout: reveal all pending elements after 1000ms so content is NEVER stuck invisible
    setTimeout(() => {
        reveals.forEach((el) => {
            el.classList.add('active');
            el.classList.remove('reveal-pending');
        });
    }, 1000);
}

/* 1b. Project Showcase Interactive Layout (Responsive Grid & Accessible Carousel) */
function initProjectShowcase() {
    const container = document.getElementById('project-cards-container');
    const gridBtn = document.getElementById('view-mode-grid');
    const carouselBtn = document.getElementById('view-mode-carousel');
    const carouselControls = document.getElementById('project-carousel-controls');
    const prevBtn = document.getElementById('proj-prev-btn');
    const nextBtn = document.getElementById('proj-next-btn');
    const counterText = document.getElementById('proj-counter-text');

    if (!container) return;

    const cards = container.querySelectorAll('.compact-card');
    const totalProjects = cards.length;
    if (totalProjects === 0) return;

    let isCarousel = false;
    let currentIndex = 0;

    const updateCarouselUI = () => {
        if (!isCarousel) return;

        if (counterText) {
            counterText.textContent = `${currentIndex + 1} / ${totalProjects}`;
        }

        if (prevBtn) {
            prevBtn.disabled = currentIndex === 0;
        }

        if (nextBtn) {
            nextBtn.disabled = currentIndex >= totalProjects - 1;
        }
    };

    const scrollToProject = (index) => {
        if (index < 0) index = 0;
        if (index >= totalProjects) index = totalProjects - 1;
        currentIndex = index;

        const targetCard = cards[currentIndex];
        if (targetCard) {
            targetCard.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'start' });
        }
        updateCarouselUI();
    };

    const enableCarouselMode = () => {
        isCarousel = true;
        container.classList.add('is-carousel');
        if (gridBtn) {
            gridBtn.classList.remove('active');
            gridBtn.setAttribute('aria-pressed', 'false');
        }
        if (carouselBtn) {
            carouselBtn.classList.add('active');
            carouselBtn.setAttribute('aria-pressed', 'true');
        }
        if (carouselControls) {
            carouselControls.style.display = 'inline-flex';
        }
        cards.forEach((c) => c.classList.add('active'));
        scrollToProject(currentIndex);
    };

    const enableGridMode = () => {
        isCarousel = false;
        container.classList.remove('is-carousel');
        if (gridBtn) {
            gridBtn.classList.add('active');
            gridBtn.setAttribute('aria-pressed', 'true');
        }
        if (carouselBtn) {
            carouselBtn.classList.remove('active');
            carouselBtn.setAttribute('aria-pressed', 'false');
        }
        if (carouselControls) {
            carouselControls.style.display = 'none';
        }
    };

    if (gridBtn) {
        gridBtn.addEventListener('click', enableGridMode);
    }

    if (carouselBtn) {
        carouselBtn.addEventListener('click', enableCarouselMode);
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            scrollToProject(currentIndex - 1);
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            scrollToProject(currentIndex + 1);
        });
    }

    let scrollTimeout;
    container.addEventListener('scroll', () => {
        if (!isCarousel) return;
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(() => {
            const scrollLeft = container.scrollLeft;
            const cardWidth = cards[0]?.offsetWidth || 300;
            const newIndex = Math.round(scrollLeft / cardWidth);
            if (newIndex !== currentIndex && newIndex >= 0 && newIndex < totalProjects) {
                currentIndex = newIndex;
                updateCarouselUI();
            }
        }, 80);
    }, { passive: true });

    container.addEventListener('keydown', (e) => {
        if (!isCarousel) return;
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            scrollToProject(currentIndex - 1);
        } else if (e.key === 'ArrowRight') {
            e.preventDefault();
            scrollToProject(currentIndex + 1);
        }
    });

    let touchStartX = 0;
    container.addEventListener('touchstart', (e) => {
        if (!isCarousel) return;
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });

    container.addEventListener('touchend', (e) => {
        if (!isCarousel) return;
        const touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                scrollToProject(currentIndex + 1);
            } else {
                scrollToProject(currentIndex - 1);
            }
        }
    }, { passive: true });
}

/* 2. Smooth Ease-Out Animated Counters */
function initAnimatedCounters() {
    const counters = document.querySelectorAll('[data-counter]');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting && !entry.target.dataset.animated) {
                entry.target.dataset.animated = 'true';
                const target = parseFloat(entry.target.getAttribute('data-counter')) || 0;
                const prefix = entry.target.getAttribute('data-prefix') || '';
                const suffix = entry.target.getAttribute('data-suffix') || '';
                const decimals = parseInt(entry.target.getAttribute('data-decimals') || '0', 10);
                const duration = 2000;
                let startTime = null;

                const step = (timestamp) => {
                    if (!startTime) startTime = timestamp;
                    const elapsed = timestamp - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    // Ease-out quad
                    const easeProgress = progress * (2 - progress);
                    const current = easeProgress * target;

                    entry.target.textContent = `${prefix}${current.toFixed(decimals)}${suffix}`;

                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    } else {
                        entry.target.textContent = `${prefix}${target.toFixed(decimals)}${suffix}`;
                    }
                };
                window.requestAnimationFrame(step);
            }
        });
    }, { threshold: 0.2 });

    counters.forEach((c) => observer.observe(c));
}

/* 3. Mobile Navigation Drawer */
function initMobileNav() {
    const toggleBtn = document.getElementById('mobile-menu-toggle');
    const drawer = document.getElementById('mobile-drawer');
    const backdrop = document.getElementById('mobile-drawer-backdrop');
    const closeBtn = document.getElementById('mobile-drawer-close');

    if (!toggleBtn || !drawer) return;

    const openMenu = () => {
        drawer.classList.remove('hidden');
        if (backdrop) backdrop.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    const closeMenu = () => {
        drawer.classList.add('hidden');
        if (backdrop) backdrop.classList.add('hidden');
        document.body.style.overflow = '';
    };

    toggleBtn.addEventListener('click', openMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);
    if (backdrop) backdrop.addEventListener('click', closeMenu);

    drawer.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', closeMenu);
    });
}

/* ==========================================================================
   4. Floating Gemini AI Assistant Widget with Markdown Rendering & Sanitization
   ========================================================================== */

/**
 * Robust Markdown parser and HTML sanitization module for AI Assistant
 */
const ChatMarkdownRenderer = (() => {
    // Configure DOMPurify hooks once if available
    let domPurifyConfigured = false;
    function setupDOMPurify() {
        if (domPurifyConfigured || typeof window === 'undefined' || !window.DOMPurify) return;
        try {
            window.DOMPurify.addHook('afterSanitizeAttributes', function (node) {
                if (node.tagName === 'A') {
                    const href = node.getAttribute('href') || '';
                    // Restrict links to safe protocols: http, https, mailto, anchor #
                    if (!/^(https?:\/\/|mailto:|#)/i.test(href)) {
                        node.removeAttribute('href');
                    } else {
                        node.setAttribute('target', '_blank');
                        node.setAttribute('rel', 'noopener noreferrer');
                    }
                }
            });
            domPurifyConfigured = true;
        } catch (e) {
            console.warn('DOMPurify hook configuration warning:', e);
        }
    }

    /**
     * Escape raw HTML entities for safe fallback
     */
    function escapeHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    }

    /**
     * Fallback markdown parser if marked is unavailable
     */
    function fallbackParse(text) {
        let safe = escapeHtml(text);

        // Code blocks: ```lang ... ```
        safe = safe.replace(/```([a-zA-Z0-9_-]*)\n([\s\S]*?)```/g, (match, lang, code) => {
            const langClass = lang ? ` class="language-${lang}"` : '';
            return `<pre><code${langClass}>${code}</code></pre>`;
        });

        // Inline code: `code`
        safe = safe.replace(/`([^`\n]+)`/g, '<code>$1</code>');

        // Headings: # through ####
        safe = safe.replace(/^#### (.*?)$/gm, '<h4>$1</h4>');
        safe = safe.replace(/^### (.*?)$/gm, '<h3>$1</h3>');
        safe = safe.replace(/^## (.*?)$/gm, '<h2>$1</h2>');
        safe = safe.replace(/^# (.*?)$/gm, '<h1>$1</h1>');

        // Bold: **text**
        safe = safe.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
        // Italic: *text* or _text_
        safe = safe.replace(/\*([^*]+)\*/g, '<em>$1</em>');
        safe = safe.replace(/_([^_]+)_/g, '<em>$1</em>');

        // Blockquotes
        safe = safe.replace(/^> (.*?)$/gm, '<blockquote><p>$1</p></blockquote>');

        // Horizontal rules
        safe = safe.replace(/^(?:---|\*\*\*|___)$/gm, '<hr>');

        // Links: [text](url) - only http, https, mailto
        safe = safe.replace(/\[([^\]]+)\]\(((?:https?:\/\/|mailto:)[^\s)]+)\)/g, '<a href="$2" target="_blank" rel="noopener noreferrer">$1</a>');

        // Lists: - or *
        safe = safe.replace(/^\s*[-*]\s+(.*?)$/gm, '<li>$1</li>');
        safe = safe.replace(/(<li>[\s\S]*?<\/li>)/g, '<ul>$1</ul>');

        // Paragraphs: split by double newlines
        return safe.split(/\n\n+/).map(p => {
            p = p.trim();
            if (!p) return '';
            if (/^<(h[1-6]|ul|ol|pre|blockquote|hr)/i.test(p)) return p;
            return `<p>${p.replace(/\n/g, '<br>')}</p>`;
        }).join('');
    }

    /**
     * Clean and prepare partial/streamed Markdown
     */
    function preparePartialMarkdown(md) {
        if (!md) return '';
        let processed = md;

        // Auto-close unfinished fenced code block if streaming
        const codeBlockMatches = processed.match(/```/g);
        if (codeBlockMatches && codeBlockMatches.length % 2 !== 0) {
            processed += '\n```\n';
        }

        // Auto-close dangling unclosed bold marker at end of stream (e.g. "**some text")
        const danglingBold = /\*\*([^*\n]+)$/.exec(processed);
        if (danglingBold) {
            processed += '**';
        }

        return processed;
    }

    /**
     * Parse and sanitize markdown content
     */
    function render(markdownText, isStreaming = false) {
        if (!markdownText) return '';
        setupDOMPurify();

        try {
            const prepared = isStreaming ? preparePartialMarkdown(markdownText) : markdownText;
            let rawHtml = '';

            if (typeof window !== 'undefined' && window.marked && typeof window.marked.parse === 'function') {
                rawHtml = window.marked.parse(prepared, {
                    gfm: true,
                    breaks: true,
                });
            } else {
                rawHtml = fallbackParse(prepared);
            }

            // Sanitize with DOMPurify
            let cleanHtml = '';
            if (typeof window !== 'undefined' && window.DOMPurify && typeof window.DOMPurify.sanitize === 'function') {
                cleanHtml = window.DOMPurify.sanitize(rawHtml, {
                    ALLOWED_TAGS: [
                        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'del',
                        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                        'ul', 'ol', 'li', 'code', 'pre', 'blockquote',
                        'table', 'thead', 'tbody', 'tr', 'th', 'td',
                        'a', 'hr', 'span', 'div',
                    ],
                    ALLOWED_ATTR: ['href', 'title', 'target', 'rel', 'class'],
                    ALLOWED_URI_REGEXP: /^(?:(?:https?|mailto):|#)/i,
                });
            } else {
                cleanHtml = rawHtml;
            }

            return cleanHtml;
        } catch (err) {
            console.error('Markdown rendering error:', err);
            return `<p>${escapeHtml(markdownText).replace(/\n/g, '<br>')}</p>`;
        }
    }

    /**
     * Enhance code blocks (copy buttons, syntax highlight), tables, and callouts
     */
    function enhanceContent(container) {
        if (!container) return;

        // 1. Tables: wrap in responsive scroll container
        container.querySelectorAll('table').forEach(table => {
            if (!table.parentElement.classList.contains('chat-table-wrapper')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'chat-table-wrapper';
                table.parentNode.insertBefore(wrapper, table);
                wrapper.appendChild(table);
            }
        });

        // 2. Blockquotes: check for [!NOTE], [!TIP], [!WARNING], [!IMPORTANT], etc.
        container.querySelectorAll('blockquote').forEach(bq => {
            const text = bq.textContent.trim();
            const calloutMatch = /^\[!(NOTE|TIP|IMPORTANT|WARNING|CAUTION)\]/i.exec(text);
            if (calloutMatch) {
                const type = calloutMatch[1].toLowerCase();
                const cleanType = type === 'caution' ? 'warning' : type;
                bq.classList.add('chat-callout', cleanType);

                const firstP = bq.querySelector('p');
                if (firstP) {
                    firstP.innerHTML = firstP.innerHTML
                        .replace(/^\[!(NOTE|TIP|IMPORTANT|WARNING|CAUTION)\]\s*<br>?/i, '')
                        .replace(/^\[!(NOTE|TIP|IMPORTANT|WARNING|CAUTION)\]\s*/i, '');

                    const header = document.createElement('div');
                    header.className = 'chat-callout-header';
                    const iconName = type === 'tip'
                        ? 'lightbulb'
                        : (type === 'warning' || type === 'caution'
                            ? 'warning'
                            : (type === 'important' ? 'priority_high' : 'info'));

                    header.innerHTML = `<span class="material-symbols-outlined" style="font-size:16px;">${iconName}</span><span>${type}</span>`;
                    bq.insertBefore(header, firstP);
                }
            }
        });

        // 3. Code blocks: syntax highlight & copy button
        container.querySelectorAll('pre code').forEach(codeEl => {
            const pre = codeEl.parentElement;
            if (pre.parentElement && pre.parentElement.classList.contains('chat-code-card')) {
                return; // already enhanced
            }

            // Syntax highlighting via highlight.js if available
            if (typeof window !== 'undefined' && window.hljs && typeof window.hljs.highlightElement === 'function') {
                try {
                    window.hljs.highlightElement(codeEl);
                } catch (e) {
                    // Plain code remains legible
                }
            }

            // Extract language label
            let lang = 'CODE';
            codeEl.classList.forEach(cls => {
                if (cls.startsWith('language-')) {
                    lang = cls.replace('language-', '').toUpperCase();
                }
            });

            // Build card wrapper
            const card = document.createElement('div');
            card.className = 'chat-code-card';

            const header = document.createElement('div');
            header.className = 'chat-code-header';
            header.innerHTML = `
                <span class="code-lang">
                    <span class="material-symbols-outlined" style="font-size:14px;">terminal</span>
                    <span>${lang}</span>
                </span>
                <button type="button" class="chat-code-copy-btn" aria-label="Copy code snippet">
                    <span class="material-symbols-outlined">content_copy</span>
                    <span>Copy</span>
                </button>
            `;

            const copyBtn = header.querySelector('.chat-code-copy-btn');
            copyBtn.addEventListener('click', async (e) => {
                e.stopPropagation();
                const codeText = codeEl.textContent;
                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(codeText);
                    } else {
                        const ta = document.createElement('textarea');
                        ta.value = codeText;
                        document.body.appendChild(ta);
                        ta.select();
                        document.execCommand('copy');
                        document.body.removeChild(ta);
                    }
                    copyBtn.innerHTML = `
                        <span class="material-symbols-outlined" style="font-size:14px; color:#10b981;">check</span>
                        <span style="color:#10b981;">Copied!</span>
                    `;
                    setTimeout(() => {
                        copyBtn.innerHTML = `
                            <span class="material-symbols-outlined">content_copy</span>
                            <span>Copy</span>
                        `;
                    }, 2000);
                } catch (err) {
                    console.error('Failed to copy code snippet:', err);
                }
            });

            pre.parentNode.insertBefore(card, pre);
            card.appendChild(header);
            card.appendChild(pre);
        });
    }

    return {
        render,
        enhanceContent,
        escapeHtml,
    };
})();

function initChatAssistant() {
    const launcher = document.getElementById('chat-launcher');
    const windowEl = document.getElementById('chat-window');
    const closeBtn = document.getElementById('chat-close');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messagesContainer = document.getElementById('chat-messages');

    if (!launcher || !windowEl || !form || !messagesContainer) return;

    // Toggle window
    launcher.addEventListener('click', () => {
        const isHidden = windowEl.classList.contains('hidden');
        if (isHidden) {
            windowEl.classList.remove('hidden');
            input.focus();
        } else {
            windowEl.classList.add('hidden');
        }
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            windowEl.classList.add('hidden');
        });
    }

    // Chat History in Session Storage
    const storageKey = 'portfolio_chat_history_v1';
    let history = [];
    try {
        const saved = sessionStorage.getItem(storageKey);
        if (saved) history = JSON.parse(saved);
    } catch (e) {
        history = [];
    }

    /**
     * Smart scroll: maintain scroll if user scrolled up, or scroll to bottom
     */
    const scrollToBottomIfNear = (force = false) => {
        const threshold = 90;
        const isNearBottom = (messagesContainer.scrollHeight - messagesContainer.scrollTop - messagesContainer.clientHeight) <= threshold;
        if (force || isNearBottom) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
    };

    /**
     * Append a message bubble to the chat container
     */
    const appendMessage = (role, text, isHistory = false) => {
        const bubble = document.createElement('div');
        bubble.className = `message-bubble ${role}`;

        if (role === 'assistant') {
            const contentDiv = document.createElement('div');
            contentDiv.className = 'chat-rendered-content';
            contentDiv.innerHTML = ChatMarkdownRenderer.render(text);
            ChatMarkdownRenderer.enhanceContent(contentDiv);
            bubble.appendChild(contentDiv);

            // Message footer with timestamp and copy button
            const footer = document.createElement('div');
            footer.className = 'chat-message-footer';

            const now = new Date();
            const timeStr = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            footer.innerHTML = `
                <span>${timeStr}</span>
                <button type="button" class="chat-copy-msg-btn" aria-label="Copy assistant response" title="Copy response">
                    <span class="material-symbols-outlined">content_copy</span>
                    <span>Copy</span>
                </button>
            `;

            const copyMsgBtn = footer.querySelector('.chat-copy-msg-btn');
            copyMsgBtn.addEventListener('click', async () => {
                try {
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        await navigator.clipboard.writeText(text);
                    } else {
                        const ta = document.createElement('textarea');
                        ta.value = text;
                        document.body.appendChild(ta);
                        ta.select();
                        document.execCommand('copy');
                        document.body.removeChild(ta);
                    }
                    copyMsgBtn.innerHTML = `
                        <span class="material-symbols-outlined" style="color:#10b981;">check</span>
                        <span style="color:#10b981;">Copied!</span>
                    `;
                    setTimeout(() => {
                        copyMsgBtn.innerHTML = `
                            <span class="material-symbols-outlined">content_copy</span>
                            <span>Copy</span>
                        `;
                    }, 2000);
                } catch (e) {
                    console.error('Copy message failed', e);
                }
            });

            bubble.appendChild(footer);
        } else {
            bubble.textContent = text;
        }

        messagesContainer.appendChild(bubble);
        scrollToBottomIfNear(true);
        return bubble;
    };

    /**
     * Render partial/streamed text onto an existing bubble smoothly
     */
    const updateStreamingBubble = (bubble, text, isStreaming = true) => {
        let contentDiv = bubble.querySelector('.chat-rendered-content');
        if (!contentDiv) {
            bubble.innerHTML = '';
            contentDiv = document.createElement('div');
            contentDiv.className = 'chat-rendered-content';
            bubble.appendChild(contentDiv);
        }

        contentDiv.innerHTML = ChatMarkdownRenderer.render(text, isStreaming);
        if (!isStreaming) {
            ChatMarkdownRenderer.enhanceContent(contentDiv);
        }
        scrollToBottomIfNear(false);
    };

    // Render existing history or welcome prompt
    if (history.length) {
        history.forEach((msg) => appendMessage(msg.role, msg.content, true));
    } else {
        appendMessage('assistant', "Hello! I'm **Victor's AI portfolio assistant**. Ask me about his software projects, systems architecture, or engineering background!");
    }

    /**
     * Send message to backend and handle response
     */
    const sendMessage = async (userText) => {
        input.value = '';
        input.disabled = true;

        appendMessage('user', userText);
        history.push({ role: 'user', content: userText });

        // Add animated typing indicator
        const typingIndicator = document.createElement('div');
        typingIndicator.className = 'message-bubble assistant';
        typingIndicator.innerHTML = `
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="font-size:12.5px; color:var(--text-light); font-weight:500;">Thinking</span>
                <span class="chat-typing-dots">
                    <span class="chat-typing-dot"></span>
                    <span class="chat-typing-dot"></span>
                    <span class="chat-typing-dot"></span>
                </span>
            </div>
        `;
        messagesContainer.appendChild(typingIndicator);
        scrollToBottomIfNear(true);

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const chatUrl = windowEl.getAttribute('data-url') || '/chat';

            const res = await fetch(chatUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    messages: history.slice(-6), // keep last 6 turns
                    session_id: 'visitor_' + (sessionStorage.getItem('chat_session_id') || Math.random().toString(36).substring(7))
                })
            });

            const data = await res.json();
            if (messagesContainer.contains(typingIndicator)) {
                messagesContainer.removeChild(typingIndicator);
            }

            if (!res.ok || !data.success) {
                const errorMsg = data.message || "I couldn't process that right now.";
                const errorBubble = appendMessage('assistant', `⚠️ **Notice:** ${errorMsg}`);
                
                // Add a retry button
                const retryBtn = document.createElement('button');
                retryBtn.className = 'chat-retry-btn';
                retryBtn.type = 'button';
                retryBtn.innerHTML = `
                    <span class="material-symbols-outlined">replay</span>
                    <span>Retry Question</span>
                `;
                retryBtn.addEventListener('click', () => {
                    sendMessage(userText);
                });
                errorBubble.querySelector('.chat-rendered-content')?.appendChild(retryBtn);
                return;
            }

            const botReply = data.reply || "I don't have enough information to answer that question accurately.";
            appendMessage('assistant', botReply);
            history.push({ role: 'assistant', content: botReply });
            sessionStorage.setItem(storageKey, JSON.stringify(history));
        } catch (err) {
            if (messagesContainer.contains(typingIndicator)) {
                messagesContainer.removeChild(typingIndicator);
            }

            const failBubble = appendMessage('assistant', "I'm having trouble connecting to the assistant right now. Please check your connection or contact Victor directly via the contact form.");
            const retryBtn = document.createElement('button');
            retryBtn.className = 'chat-retry-btn';
            retryBtn.type = 'button';
            retryBtn.innerHTML = `
                <span class="material-symbols-outlined">replay</span>
                <span>Retry</span>
            `;
            retryBtn.addEventListener('click', () => {
                sendMessage(userText);
            });
            failBubble.querySelector('.chat-rendered-content')?.appendChild(retryBtn);
        } finally {
            input.disabled = false;
            input.focus();
        }
    };

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const text = input.value.trim();
        if (!text) return;
        sendMessage(text);
    });

    // Expose renderer on window for automated testing and extensions
    window.ChatMarkdownRenderer = ChatMarkdownRenderer;
}

/* 5. Contact Form AJAX Submission */
function initContactForm() {
    const form = document.getElementById('portfolio-contact-form');
    if (!form) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Send Message';
        const feedbackContainer = document.getElementById('contact-form-feedback');

        // Clear previous error messages & highlights
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        form.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.input-shell, .textarea-shell').forEach(el => el.classList.remove('has-error'));
        if (feedbackContainer) {
            feedbackContainer.innerHTML = '';
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="material-symbols-outlined" style="animation: spin 1s linear infinite; vertical-align: middle;">progress_activity</span> Sending...';
        }

        const formData = new FormData(form);
        const actionUrl = form.getAttribute('action') || '/contact';

        try {
            const res = await fetch(actionUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await res.json();

            if (res.ok && data.success) {
                form.reset();
                showToast(data.message || 'Your message has been received! Thank you.', 'success');
                if (feedbackContainer) {
                    feedbackContainer.innerHTML = `
                        <div class="form-alert form-alert-success" role="alert">
                            <span class="material-symbols-outlined alert-icon" aria-hidden="true">check_circle</span>
                            <div>
                                <strong>Message Delivered!</strong>
                                <p>${data.message || 'Your message has been received! Thank you.'}</p>
                            </div>
                        </div>`;
                }
            } else {
                const errorMsg = data.message || 'Please correct the errors in the form.';
                showToast(errorMsg, 'error');

                if (data.errors) {
                    Object.entries(data.errors).forEach(([field, messages]) => {
                        const inputEl = form.querySelector(`[name="${field}"]`);
                        if (inputEl) {
                            inputEl.classList.add('is-invalid');
                            const shell = inputEl.closest('.input-shell') || inputEl.closest('.textarea-shell');
                            if (shell) {
                                shell.classList.add('has-error');
                            }
                            const errDiv = document.createElement('div');
                            errDiv.className = 'invalid-feedback';
                            errDiv.textContent = messages[0];
                            const formGroup = inputEl.closest('.form-group');
                            if (formGroup) {
                                formGroup.appendChild(errDiv);
                            } else {
                                inputEl.parentNode.appendChild(errDiv);
                            }
                        }
                    });
                }
            }
        } catch (err) {
            showToast('Unable to submit your message. Please check your network connection and try again.', 'error');
            if (feedbackContainer) {
                feedbackContainer.innerHTML = `
                    <div class="form-alert form-alert-error" role="alert">
                        <span class="material-symbols-outlined alert-icon" aria-hidden="true">error</span>
                        <div>
                            <strong>Delivery Issue</strong>
                            <p>Unable to submit your message. Please check your network connection and try again.</p>
                        </div>
                    </div>`;
            }
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        }
    });
}

/* 6. Floating Toast Notifications */
function showToast(message, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'toast-container';
        document.body.appendChild(container);
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    const icon = type === 'success' ? 'check_circle' : 'error';
    toast.innerHTML = `<span class="material-symbols-outlined">${icon}</span> <span>${message}</span>`;
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(-10px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 4500);
}

function initToastAutoDismiss() {
    document.querySelectorAll('.toast').forEach((t) => {
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transform = 'translateY(-10px)';
            t.style.transition = 'all 0.3s ease';
            setTimeout(() => t.remove(), 300);
        }, 4500);
    });
}
