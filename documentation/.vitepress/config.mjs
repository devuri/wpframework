import {
    defineConfig
} from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
    logo: '/logo.png',
    lang: 'en-US',
    title: 'Raydium',
    description: 'Effortlessly develop scalable WordPress applications that support multiple tenants from a single installation.',
    srcDir: 'src',
    outDir: '../docs',
    themeConfig: {
        // https://vitepress.dev/reference/default-theme-config
        nav: [{
                text: 'Home',
                link: '/'
            },
            {
                text: 'Guide',
                link: '/guide/what-is-raydium'
            },
            {
                text: 'Reference',
                link: '/reference/app-configs'
            }
        ],

        sidebar: [{
                text: 'Quick Start',
                collapsible: true,
                collapsed: false,
                items: [{
                        text: 'Why Raydium',
                        link: '/guide/why-raydium'
                    },
                    {
                        text: 'What Is Raydium',
                        link: '/guide/what-is-raydium'
                    },
                    {
                        text: 'Getting Started',
                        link: '/guide/getting-started'
                    },
                    {
                        text: 'Installation',
                        link: '/guide/installation'
                    },
                    {
                        text: 'Migration',
                        link: '/guide/migration'
                    },
                    {
                        text: 'Deploy',
                        link: '/guide/deploy'
                    }
                ]
            },
            {
                text: 'Customization',
                collapsible: true,
                collapsed: true,
                items: [{
                    text: 'App Configs',
                    link: '/customization/app-config'
                }]
            },
            {
                text: 'Upgrades',
                collapsible: true,
                collapsed: true,
                items: [{
                    text: '^0.0.5',
                    link: '/upgrade/env-config-upgrade'
                }, ]
            },
            {
                text: 'Advanced',
                items: [{
                        text: 'Premium Plugins',
                        link: '/premium-plugins'
                    },
                    {
                        text: 'Multi-Tenant',
                        link: '/multi-tenant'
                    }
                ]
            },
            {
                text: 'Reference',
                collapsible: true,
                collapsed: true,
                items: [{
                        text: 'Configs',
                        link: '/reference/configuration'
                    },
                    {
                        text: 'Environment',
                        link: '/reference/environment-variables'
                    },
                    {
                        text: 'code',
                        link: '/docs/code'
                    }
                ]
            },
        ],
        socialLinks: [{
            icon: 'github',
            link: 'https://github.com/devuri/wpframework'
        }],
        search: {
            provider: 'local'
        },
        footer: {
            message: 'Released under the <a href="https://github.com/devuri/wpframework/blob/master/LICENSE">MIT License</a>.',
            copyright: 'Copyright © <a href="https://github.com/devuri">Uriel Wilson</a>'
        }
    }


})
