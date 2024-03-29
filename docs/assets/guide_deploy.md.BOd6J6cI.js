import{_ as e,c as i,o as s,a4 as o}from"./chunks/framework.CPs9Ukbn.js";const m=JSON.parse('{"title":"Deploy Your Raydium Site","description":"","frontmatter":{},"headers":[],"relativePath":"guide/deploy.md","filePath":"guide/deploy.md"}'),a={name:"guide/deploy.md"},t=o('<h1 id="deploy-your-raydium-site" tabindex="-1">Deploy Your Raydium Site <a class="header-anchor" href="#deploy-your-raydium-site" aria-label="Permalink to &quot;Deploy Your Raydium Site&quot;">​</a></h1><p>Deploying a Raydium-powered WordPress site involves several considerations, tailored to accommodate Raydium&#39;s unique structure and dependencies, including Composer management:</p><ul><li>Your project, inclusive of Raydium and WordPress, resides in the root directory, with <code>public</code> serving as the web root containing the <code>public/content</code> directory for WordPress themes, plugins, and uploads.</li><li>The <code>vendor</code> directory and <code>.env</code> file, essential for Raydium and WordPress configurations, are located outside the web root for enhanced security.</li><li>Key deployment scripts are defined in your <code>composer.json</code>, facilitating tasks such as dependency installation and environment setup.</li></ul><h2 id="build-and-test-locally" tabindex="-1">Build and Test Locally <a class="header-anchor" href="#build-and-test-locally" aria-label="Permalink to &quot;Build and Test Locally&quot;">​</a></h2><p>To ensure a smooth deployment, thoroughly test your site in a local environment:</p><ol><li><p>Confirm that your local server is configured to serve the <code>public</code> directory as the web root.</p></li><li><p>Conduct a comprehensive review of your site&#39;s functionality, including themes, plugins, and custom content, to ensure everything operates as intended.</p></li><li><p>While WordPress dynamically renders pages, ensure any asset build processes (for custom themes or plugins) are executed, and the resulting assets are correctly placed within the <code>public/content</code> directory.</p></li><li><p>Run Composer to install or update dependencies, ensuring compatibility and functionality:</p><div class="language-sh vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">sh</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">composer</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> install</span></span></code></pre></div></li></ol><h2 id="setting-a-public-base-path" tabindex="-1">Setting a Public Base Path <a class="header-anchor" href="#setting-a-public-base-path" aria-label="Permalink to &quot;Setting a Public Base Path&quot;">​</a></h2><p>For sites served from a subdirectory (e.g., <code>https://yourdomain.com/blog</code>), adjust the <code>WP_HOME</code> and <code>WP_SITEURL</code> in your <code>.env</code> file:</p><div class="language-shell vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">shell</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">WP_HOME</span><span style="--shiki-light:#D73A49;--shiki-dark:#F97583;">=</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;">&#39;https://yourdomain.com/blog&#39;</span></span>\n<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">WP_SITEURL</span><span style="--shiki-light:#D73A49;--shiki-dark:#F97583;">=</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;">&quot;${</span><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">WP_HOME</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;">}/wp&quot;</span></span></code></pre></div><p>This adjustment is needed for WordPress to generate accurate URLs for assets and pages.</p><h2 id="platform-guides" tabindex="-1">Platform Guides <a class="header-anchor" href="#platform-guides" aria-label="Permalink to &quot;Platform Guides&quot;">​</a></h2><h3 id="general-web-hosting-shared-vps-dedicated" tabindex="-1">General Web Hosting (Shared, VPS, Dedicated) <a class="header-anchor" href="#general-web-hosting-shared-vps-dedicated" aria-label="Permalink to &quot;General Web Hosting (Shared, VPS, Dedicated)&quot;">​</a></h3><p>For traditional web hosting platforms:</p><ol><li><p>Upload your entire project, excluding the <code>vendor</code> directory. Ensure the <code>public</code> directory is set as the web root.</p></li><li><p>Run Composer on the server (if supported) to install dependencies:</p><div class="language-sh vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">sh</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">composer</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> install</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;"> --no-dev</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;"> --optimize-autoloader</span></span></code></pre></div></li><li><p>Migrate your database if moving from a different environment. Update the <code>.env</code> file with production environment settings.</p></li></ol><h3 id="managed-wordpress-hosting" tabindex="-1">Managed WordPress Hosting <a class="header-anchor" href="#managed-wordpress-hosting" aria-label="Permalink to &quot;Managed WordPress Hosting&quot;">​</a></h3><p>For managed WordPress hosting that supports Composer:</p><ol><li><p>Check if your host supports Git-based deployments or direct Composer usage on the server.</p></li><li><p>Deploy your project via Git or other supported methods, ensuring the <code>public</code> directory aligns with the web root configuration.</p></li><li><p>Use SSH or hosting tools to run Composer on the server, installing necessary PHP dependencies.</p></li></ol><h3 id="cloud-platforms-aws-google-cloud-azure" tabindex="-1">Cloud Platforms (AWS, Google Cloud, Azure) <a class="header-anchor" href="#cloud-platforms-aws-google-cloud-azure" aria-label="Permalink to &quot;Cloud Platforms (AWS, Google Cloud, Azure)&quot;">​</a></h3><p>Cloud platforms offer flexibility for deploying Composer-managed WordPress sites:</p><ol><li><p>Choose a suitable service (e.g., VM instances, container services) that supports custom configurations and Composer.</p></li><li><p>Configure the service to set the <code>public</code> directory as the web root and secure the <code>vendor</code> and <code>.env</code> files outside the web root.</p></li><li><p>Utilize cloud-based CI/CD pipelines to automate deployments, including Composer dependency installations and environment configurations.</p></li></ol><h2 id="what-s-next" tabindex="-1">What&#39;s Next? <a class="header-anchor" href="#what-s-next" aria-label="Permalink to &quot;What&#39;s Next?&quot;">​</a></h2><ul><li>Post-deployment, actively monitor your site&#39;s performance and security, adjusting configurations as necessary.</li><li>Evaluate CDN integration for further performance enhancements, especially for global audiences.</li><li>Regularly update WordPress core, plugins, themes, and Composer dependencies to ensure ongoing site integrity and performance.</li></ul>',22),n=[t];function r(l,d,p,c,h,u){return s(),i("div",null,n)}const y=e(a,[["render",r]]);export{m as __pageData,y as default};
