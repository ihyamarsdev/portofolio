<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Representation
    |--------------------------------------------------------------------------
    |
    | These values specify representations for different visual parts of buttons.
    |
    | Visual block/Visual container representation:
    | - 'block_prefix' represents a share buttons block start
    | - 'block_suffix' represents a share buttons block end
    | Each element representation:
    | - 'element_prefix' represents an element start
    | - 'element_suffix' represents an element end
    |
    */

    'block_prefix' => '<div id="social-buttons">',
    'block_suffix' => '</div>',
    'element_prefix' => '',
    'element_suffix' => '',

    /*
    |--------------------------------------------------------------------------
    | Share buttons
    |--------------------------------------------------------------------------
    |
    | These values specify configuration settings for each social media button.
    | The settings include a sharing url, a default text in the url, some extras.
    | The format of substitution depends on a templater (see Templaters section).
    | Note: It is allowed to provide a site's url to the copylink button, because
    | some people might want to see it there, even though using a hash is enough.
    |
    */

    'buttons' => [
        'bluesky' => [
            'url' => 'https://bsky.app/intent/compose?text=:url%20:text',
            'text' => 'Default share text',
        ],
        'copylink' => [
            'url' => ':url',
            'extra' => [
                'raw' => 'true',
                'hash' => 'true',
            ],
        ],
        'evernote' => [
            'url' => 'https://www.evernote.com/clip.action?url=:url&t=:text',
            'text' => 'Default share text',
        ],
        'facebook' => [
            'url' => 'https://www.facebook.com/sharer/sharer.php?u=:url&quote=:text',
            'text' => 'Default share text',
        ],
        'hackernews' => [
            'url' => 'https://news.ycombinator.com/submitlink?t=:text&u=:url',
            'text' => 'Default share text',
        ],
        'linkedin' => [
            'url' => 'https://www.linkedin.com/sharing/share-offsite?mini=true&url=:url&title=:text&summary=:summary',
            'text' => 'Default share text',
            'extra' => [
                'summary' => '',
            ],
        ],
        'mailto' => [
            'url' => 'mailto:?subject=:text&body=:url',
            'text' => 'Default share text',
        ],
        'mastodon' => [
            'url' => 'https://mastodon.social/share?text=:text&url=:url',
            'text' => 'Default share text',
        ],
        'pinterest' => [
            'url' => 'https://pinterest.com/pin/create/button/?url=:url',
        ],
        'pocket' => [
            'url' => 'https://getpocket.com/edit?url=:url&title=:text',
            'text' => 'Default share text',
        ],
        'reddit' => [
            'url' => 'https://www.reddit.com/submit?title=:text&url=:url',
            'text' => 'Default share text',
        ],
        'skype' => [
            'url' => 'https://web.skype.com/share?url=:url&text=:text&source=button',
            'text' => 'Default share text',
        ],
        'telegram' => [
            'url' => 'https://telegram.me/share/url?url=:url&text=:text',
            'text' => 'Default share text',
        ],
        'tumblr' => [
            'url' => 'https://www.tumblr.com/share?v=3&u=:url&t=:text',
            'text' => 'Default share text',
        ],
        'threads' => [
            'url' => 'https://www.threads.net/intent/post?text=:text%20:url',
            'text' => 'Default share text',
        ],
        'twitter' => [
            'url' => 'https://x.com/intent/tweet?text=:text&url=:url',
            'text' => 'Default share text',
        ],
        'vkontakte' => [
            'url' => 'https://vk.com/share.php?url=:url&title=:text',
            'text' => 'Default share text',
        ],
        'whatsapp' => [
            'url' => 'https://wa.me/?text=:url%20:text',
            'text' => 'Default share text',
        ],
        'xing' => [
            'url' => 'https://www.xing.com/spi/shares/new?url=:url',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Templates
    |--------------------------------------------------------------------------
    |
    | These values specify link templates for each of the social media buttons.
    | The format of substitution depends on a templater (see Templaters section).
    | Note: Don't remove the social-button class from links because it's used in js.
    |
    */

    'templates' => [
        'bluesky' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12 10.8c-1.087-2.114-4.046-6.053-6.798-7.995C2.566.944 1.561 1.266.902 1.565.139 1.908 0 3.08 0 3.768c0 .69.378 5.65.624 6.479.815 2.736 3.713 3.66 6.383 3.364.136-.02.275-.039.415-.056-.138.022-.276.04-.415.056-3.912.58-7.387 2.005-2.83 7.078 5.013 5.19 6.87-1.113 7.823-4.308.953 3.195 2.05 9.271 7.733 4.308 4.267-4.308 1.172-6.498-2.74-7.078a8.741 8.741 0 0 1-.415-.056c.14.017.279.036.415.056 2.67.297 5.568-.628 6.383-3.364.246-.828.624-5.79.624-6.478 0-.69-.139-1.861-.902-2.206-.659-.298-1.664-.62-4.3 1.24C16.046 4.748 13.087 8.687 12 10.8Z"/></svg></a>',
        'copylink' => '<a href=":url" class="social-button:class" id="clip":title:rel><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg></a>',
        'evernote' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M17.6 1.1H14V.6c0-.3-.3-.6-.6-.6H10c-.4 0-.7.3-.7.6v.5H5.8C4.8 1.1 4 2 4 3v18.7c0 .7.6 1.3 1.4 1.3h13.3c.7 0 1.3-.6 1.3-1.3V3c0-1-.9-1.9-2.4-1.9zm-3.3 2.6h2.2c.7 0 1.2.5 1.2 1.2s-.5 1.2-1.2 1.2h-2.2c-.7 0-1.2-.5-1.2-1.2s.5-1.2 1.2-1.2zM8 19.5H7c-.4 0-.8-.3-.8-.8s.3-.8.8-.8h1c.4 0 .8.3.8.8s-.4.8-.8.8zm0-3.5H7c-.4 0-.8-.3-.8-.8s.3-.8.8-.8h1c.4 0 .8.3.8.8s-.4.8-.8.8zm0-3.5H7c-.4 0-.8-.3-.8-.8s.3-.8.8-.8h1c.4 0 .8.3.8.8s-.4.8-.8.8zm9 7H10c-.4 0-.8-.3-.8-.8s.3-.8.8-.8h7c.4 0 .8.3.8.8s-.4.8-.8.8zm0-3.5h-7c-.4 0-.8-.3-.8-.8s.3-.8.8-.8h7c.4 0 .8.3.8.8s-.4.8-.8.8zm0-3.5h-7c-.4 0-.8-.3-.8-.8s.3-.8.8-.8h7c.4 0 .8.3.8.8s-.4.8-.8.8z"/></svg></a>',
        'facebook' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></a>',
        'hackernews' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M0 24V0h24v24H0zM6.951 5.896l4.112 7.708v5.064h1.583v-4.972l4.148-7.799h-1.749l-2.457 4.875c-.372.745-.688 1.434-.688 1.434s-.297-.708-.651-1.406L8.831 5.896h-1.88z"/></svg></a>',
        'linkedin' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg></a>',
        'mailto' => '<a href=":url" class="social-button:class":id:title:rel><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/></svg></a>',
        'mastodon' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M23.268 5.313c-.35-2.578-2.617-4.61-5.304-5.004C17.51.242 15.792 0 11.813 0h-.03c-3.98 0-4.835.242-5.288.309C3.882.692 1.496 2.518.917 5.127.64 6.412.61 7.837.661 9.143c.074 1.874.088 3.745.26 5.611.118 1.24.325 2.47.62 3.68.55 2.237 2.777 4.098 4.96 4.857 2.336.792 4.849.923 7.256.38.265-.061.527-.132.786-.213.585-.184 1.27-.39 1.774-.753a.057.057 0 0 0 .023-.043v-1.809a.052.052 0 0 0-.02-.041.053.053 0 0 0-.046-.01 20.282 20.282 0 0 1-4.709.545c-2.73 0-3.463-1.284-3.674-1.818a5.593 5.593 0 0 1-.319-1.433.053.053 0 0 1 .066-.054c1.517.363 3.072.546 4.632.546.376 0 .75 0 1.125-.01 1.57-.044 3.224-.124 4.768-.422.038-.008.077-.015.11-.024 2.435-.464 4.753-1.92 4.989-5.604.008-.145.03-1.52.03-1.67.002-.512.167-3.63-.024-5.545zm-3.748 9.195h-2.561V8.29c0-1.309-.55-1.976-1.67-1.976-1.23 0-1.846.79-1.846 2.35v3.403h-2.546V8.663c0-1.56-.617-2.35-1.848-2.35-1.112 0-1.668.668-1.67 1.977v6.218H4.822V8.102c0-1.31.337-2.35 1.011-3.12.696-.77 1.608-1.164 2.74-1.164 1.311 0 2.302.5 2.962 1.498l.638 1.06.638-1.06c.66-.999 1.65-1.498 2.96-1.498 1.13 0 2.043.395 2.74 1.164.675.77 1.012 1.81 1.012 3.12z"/></svg></a>',
        'pinterest' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 0 1 .083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/></svg></a>',
        'pocket' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M18.813 10.259l-5.646 5.419c-.32.305-.73.458-1.167.458-.437 0-.847-.153-1.167-.458l-5.646-5.419a1.24 1.24 0 0 1 0-1.796 1.342 1.342 0 0 1 1.854 0l4.959 4.754 4.96-4.754a1.342 1.342 0 0 1 1.853 0 1.24 1.24 0 0 1 0 1.796zm4.831-7.138H.356C.16 3.121 0 3.281 0 3.469v8.08c0 6.383 5.67 11.588 12 11.588s12-5.205 12-11.588V3.469c0-.188-.16-.348-.356-.348z"/></svg></a>',
        'reddit' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm5.01 4.744c.688 0 1.25.561 1.25 1.249a1.25 1.25 0 0 1-2.498.056l-2.597-.547-.8 3.747c1.824.07 3.48.632 4.674 1.488.308-.309.73-.491 1.207-.491.968 0 1.754.786 1.754 1.754 0 .716-.435 1.333-1.01 1.614a3.111 3.111 0 0 1 .042.52c0 2.694-3.13 4.87-7.004 4.87-3.874 0-7.004-2.176-7.004-4.87 0-.183.015-.366.043-.534A1.748 1.748 0 0 1 4.028 12c0-.968.786-1.754 1.754-1.754.463 0 .898.196 1.207.49 1.207-.883 2.878-1.43 4.744-1.487l.885-4.182a.342.342 0 0 1 .14-.197.35.35 0 0 1 .238-.042l2.906.617a1.214 1.214 0 0 1 1.108-.701zM9.25 12C8.561 12 8 12.562 8 13.25c0 .687.561 1.248 1.25 1.248.687 0 1.248-.561 1.248-1.249 0-.688-.561-1.249-1.249-1.249zm5.5 0c-.687 0-1.248.561-1.248 1.25 0 .687.561 1.248 1.249 1.248.688 0 1.249-.561 1.249-1.249 0-.687-.562-1.249-1.25-1.249zm-5.466 3.99a.327.327 0 0 0-.231.094.33.33 0 0 0 0 .463c.842.842 2.484.913 2.961.913.477 0 2.105-.056 2.961-.913a.361.361 0 0 0 .029-.463.33.33 0 0 0-.464 0c-.547.533-1.684.73-2.512.73-.828 0-1.979-.196-2.512-.73a.326.326 0 0 0-.232-.095z"/></svg></a>',
        'skype' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12.069 18.874c-4.023 0-5.82-1.979-5.82-3.464 0-.765.561-1.296 1.333-1.296 1.723 0 1.273 2.477 4.487 2.477 1.641 0 2.55-.895 2.55-1.811 0-.551-.269-1.16-1.326-1.43l-3.378-.895c-2.724-.724-3.213-2.286-3.213-3.346C6.702 7.597 9.39 6.55 11.99 6.55c2.537 0 5.476 1.396 5.476 3.299 0 .789-.607 1.257-1.389 1.257-1.476 0-1.227-2.045-4.306-2.045-1.473 0-2.281.66-2.281 1.599s1.116 1.258 2.086 1.498l2.523.618c2.761.678 3.408 2.285 3.408 3.459-.001 2.285-1.755 3.639-5.438 3.639zm11.931-7.485a11.245 11.245 0 0 1 .012 1.57A11.9 11.9 0 0 1 12.45 24.01c-.544.006-1.084-.024-1.62-.088a6.994 6.994 0 0 1-9.733-9.733 11.9 11.9 0 0 1 9.9-11.898 6.995 6.995 0 0 1 9.938 9.1z"/></svg></a>',
        'telegram' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg></a>',
        'threads' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.473 12.01v-.017c.027-3.579.879-6.43 2.525-8.482C5.845 1.205 8.6.024 12.18 0h.014c2.746.02 5.043.725 6.826 2.098 1.677 1.29 2.858 3.13 3.509 5.467l-2.04.569c-1.104-3.96-3.898-5.984-8.304-6.015-2.91.022-5.11.936-6.54 2.717C4.307 6.504 3.616 8.914 3.594 12c.022 3.09.714 5.5 2.057 7.164 1.43 1.783 3.631 2.698 6.54 2.717 2.623-.02 4.358-.631 5.689-2.049 1.248-1.33 1.9-3.326 1.9-5.795 0-.422-.023-.828-.068-1.214h-7.51v-2.05h9.574c.16.787.24 1.604.24 2.437 0 3.174-.87 5.747-2.514 7.44-1.524 1.568-3.649 2.354-6.316 2.35z"/></svg></a>',
        'tumblr' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M14.563 24c-5.093 0-7.031-3.756-7.031-6.411V9.747H5.116V6.648c3.63-1.313 4.512-4.596 4.71-6.469C9.84.051 9.941 0 9.999 0h3.517v6.114h4.801v3.633h-4.82v7.47c.016 1.001.375 2.371 2.207 2.371h.09c.631-.02 1.486-.205 1.936-.419l1.156 3.425c-.436.636-2.4 1.374-4.304 1.406z"/></svg></a>',
        'twitter' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></a>',
        'vkontakte' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M15.684 0H8.316C1.592 0 0 1.592 0 8.316v7.368C0 22.408 1.592 24 8.316 24h7.368C22.408 24 24 22.408 24 15.684V8.316C24 1.592 22.391 0 15.684 0zm3.692 17.123h-1.744c-.66 0-.864-.525-2.05-1.727-1.033-1-1.49-1.135-1.744-1.135-.356 0-.458.102-.458.593v1.575c0 .424-.135.678-1.253.678-1.846 0-3.896-1.118-5.335-3.202C4.624 10.857 4.03 8.57 4.03 8.096c0-.254.102-.491.593-.491h1.744c.44 0 .61.203.78.677.863 2.49 2.303 4.675 2.896 4.675.22 0 .322-.102.322-.66V9.721c-.068-1.185-.695-1.287-.695-1.71 0-.203.17-.407.44-.407h2.744c.373 0 .508.203.508.643v3.473c0 .372.17.508.271.508.22 0 .407-.136.814-.542 1.253-1.406 2.151-3.574 2.151-3.574.119-.254.322-.491.762-.491h1.744c.525 0 .644.27.525.643-.22 1.017-2.354 4.031-2.354 4.031-.186.305-.254.44 0 .78.186.254.796.779 1.202 1.253.745.847 1.32 1.558 1.473 2.05.17.49-.085.745-.576.745z"/></svg></a>',
        'whatsapp' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413z"/></svg></a>',
        'xing' => '<a href=":url" class="social-button:class":id:title:rel target="_blank" rel="noopener noreferrer"><svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M18.188 0c-.517 0-.741.325-.927.66 0 0-7.455 13.224-7.702 13.657.015.024 4.919 9.023 4.919 9.023.17.308.436.66.967.66h3.454c.211 0 .375-.078.463-.22.089-.151.089-.346-.009-.536l-4.879-8.916c-.004-.006-.004-.016 0-.022L22.139.756c.095-.191.097-.387.006-.535C22.056.078 21.894 0 21.686 0h-3.498zM3.648 4.74c-.211 0-.385.074-.473.216-.09.149-.078.339.02.528l2.308 4.002c.016.016.016.02 0 .041L1.86 16.051c-.099.188-.093.381 0 .529.085.142.25.22.465.22h3.461c.518 0 .766-.348.945-.667l3.734-6.609-2.378-4.155c-.172-.315-.434-.629-.962-.629H3.648v.001z"/></svg></a>',
    ],

    /*
    |--------------------------------------------------------------------------
    | Templaters
    |--------------------------------------------------------------------------
    |
    | This package uses a simple template engine to substitute values in different
    | configuration settings and templates. If you want to change the substitution
    | format, feel free to use your favorite template engine (in this case it is
    | recommended to introduce an adapter that conforms to the Templater interface).
    |
    */

    'templater' => \Kudashevs\ShareButtons\Templaters\LaravelTemplater::class,

    'url_templater' => \Kudashevs\ShareButtons\Templaters\LaravelTemplater::class,

];
