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
	  base: '/wpframework/',
    outDir: '../docs',
    themeConfig: {
        // https://vitepress.dev/reference/default-theme-config
        nav: [{
                text: 'Home',
                link: '/'
            },
            {
                text: 'Guide',
                link: '/overview/what-is-raydium'
            },
            {
                text: 'Reference',
                link: '/reference/environment-vars'
            }
        ],

        sidebar: [
			{
                text: 'Overview',
                collapsible: true,
                collapsed: true,
                items: [
					{
                        text: 'Why Raydium',
                        link: '/overview/why-raydium'
                    },
                    {
                        text: 'What Is Raydium',
                        link: '/overview/what-is-raydium'
                    },
					{
                        text: 'Modern Development',
                        link: '/overview/modern-development'
                    },
					{
                        text: 'Is it Still WordPress',
                        link: '/overview/is-it-wordpress'
                    }
                ]
            },
			{
                text: 'Quick Start',
                collapsible: true,
                collapsed: false,
                items: [
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
                    },
					{
                        text: 'Environments',
                        link: '/guide/environments'
                    },
					{
                        text: 'Environment File',
                        link: '/guide/environment-file'
                    }
                ]
            },
            {
                text: 'Customization',
                collapsible: true,
                collapsed: true,
                items: [
					{
                    text: 'App Configs',
                    link: '/customization/app-config'
					},
					{
	                    text: 'Constants',
	                    link: '/customization/constants'
	                }
				]
            },
			{
				text: 'Multi-Tenant',
				collapsible: true,
				collapsed: true,
				items: [
					{
						text: 'Overview',
						link: '/multi-tenant/overview'
					},
					{
						text: 'Configuration',
						link: '/multi-tenant/tenancy-config'
					},
					{
						text: 'Isolation',
						link: '/multi-tenant/isolation'
					},
					{
						text: 'Architecture',
						link: '/multi-tenant/architecture'
					},
				]
			},
			{
				text: 'Tutorials',
				collapsible: true,
				collapsed: true,
				items: [{
					text: 'Overview',
					link: '/tutorials/overview'
				},]
			},
            {
                text: 'Reference',
                collapsible: true,
                collapsed: true,
                items: [
					{
                        text: 'Configs',
                        link: '/reference/configuration'
                    },
                    {
                        text: 'Environment',
                        link: '/reference/environment-vars'
                    },
					{
                        text: 'Plugin',
                        link: '/reference/plugin'
                    },
					{
                        text: 'Lifecycle',
                        link: '/reference/lifecycle'
                    },
					{
                        text: 'App',
                        link: '/reference/app-component'
                    },
					{
						text: 'Kernel',
						link: '/reference/kernel'
					},
					{
						text: 'Setup',
						link: '/reference/setup'
					},
					{
						text: 'Tenancy',
						link: '/reference/tenancy'
					},
					{
						text: 'Switcher',
						link: '/reference/switcher'
					},
					{
						text: 'Terminate',
						link: '/reference/terminate'
					},
					{
						text: 'Architecture',
						link: '/reference/architecture'
					},
                    {
                        text: 'Code',
                        link: 'https://devuri.github.io/wpframework/code/'
                    }
                ]
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
				items: [
					{
						text: 'Premium Plugins',
						link: '/premium-plugins'
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
