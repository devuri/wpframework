import{_ as e,c as a,o as n,a4 as t}from"./chunks/framework.ZNwHIQsX.js";const p=JSON.parse('{"title":"Multi-Tenancy in Raydium","description":"","frontmatter":{},"headers":[],"relativePath":"multi-tenant/architecture.md","filePath":"multi-tenant/architecture.md"}'),i={name:"multi-tenant/architecture.md"},o=t('<h1 id="multi-tenancy-in-raydium" tabindex="-1">Multi-Tenancy in Raydium <a class="header-anchor" href="#multi-tenancy-in-raydium" aria-label="Permalink to &quot;Multi-Tenancy in Raydium&quot;">​</a></h1><p>In Raydium&#39;s multi-tenant architecture, each tenant operates as an independent entity within a shared framework, ensuring maximum flexibility and customization. A key aspect of this architecture is that each tenant has its own dedicated database and the ability to utilize its own configuration settings. This setup provides several advantages, including data isolation, security, and tailored experiences for each tenant.</p><h2 id="tenant-specific-databases" tabindex="-1">Tenant-Specific Databases <a class="header-anchor" href="#tenant-specific-databases" aria-label="Permalink to &quot;Tenant-Specific Databases&quot;">​</a></h2><h3 id="isolation-and-security" tabindex="-1">Isolation and Security <a class="header-anchor" href="#isolation-and-security" aria-label="Permalink to &quot;Isolation and Security&quot;">​</a></h3><p>Each tenant having its own database means that the data for each tenant is completely isolated from others. This isolation enhances security by ensuring that no tenant can access the data of another, intentionally or accidentally.</p><h3 id="customization-and-scalability" tabindex="-1">Customization and Scalability <a class="header-anchor" href="#customization-and-scalability" aria-label="Permalink to &quot;Customization and Scalability&quot;">​</a></h3><p>Dedicated databases allow for customization at the data structure level, enabling tenants to have unique schemas that best fit their specific needs. It also aids in scaling, as each database can be scaled independently based on the tenant&#39;s requirements.</p><h3 id="maintenance-and-backup" tabindex="-1">Maintenance and Backup <a class="header-anchor" href="#maintenance-and-backup" aria-label="Permalink to &quot;Maintenance and Backup&quot;">​</a></h3><p>With separate databases, maintenance operations (like backups, updates, or optimizations) can be performed on a per-tenant basis, reducing the risk of affecting other tenants and enabling more tailored maintenance schedules.</p><h2 id="tenant-specific-configurations" tabindex="-1">Tenant-Specific Configurations <a class="header-anchor" href="#tenant-specific-configurations" aria-label="Permalink to &quot;Tenant-Specific Configurations&quot;">​</a></h2><h3 id="flexibility" tabindex="-1">Flexibility <a class="header-anchor" href="#flexibility" aria-label="Permalink to &quot;Flexibility&quot;">​</a></h3><p>Tenants can define their own <code>app.php</code> configurations within their designated configuration directories (e.g., <code>config/{tenant_id}/app.php</code>). This flexibility allows for tenant-specific settings like themes, plugins, performance optimizations, and feature toggles.</p><h3 id="independence" tabindex="-1">Independence <a class="header-anchor" href="#independence" aria-label="Permalink to &quot;Independence&quot;">​</a></h3><p>Tenants can operate independently from each other, making changes to their configurations without the risk of impacting other tenants. This independence is crucial for businesses that cater to diverse clients with varying requirements.</p><h3 id="streamlined-management" tabindex="-1">Streamlined Management <a class="header-anchor" href="#streamlined-management" aria-label="Permalink to &quot;Streamlined Management&quot;">​</a></h3><p>While tenants have the freedom to customize their configurations, central policies and updates can still be enforced at the framework level, ensuring consistency where necessary while allowing for customization.</p><h2 id="implementation-considerations" tabindex="-1">Implementation Considerations <a class="header-anchor" href="#implementation-considerations" aria-label="Permalink to &quot;Implementation Considerations&quot;">​</a></h2><h3 id="unique-tenant-identification" tabindex="-1">Unique Tenant Identification <a class="header-anchor" href="#unique-tenant-identification" aria-label="Permalink to &quot;Unique Tenant Identification&quot;">​</a></h3><p>Each tenant is typically identified by a unique identifier (UUID), which is used to associate the correct configuration settings for each tenant.</p><h3 id="configuration-precedence" tabindex="-1">Configuration Precedence <a class="header-anchor" href="#configuration-precedence" aria-label="Permalink to &quot;Configuration Precedence&quot;">​</a></h3><p>The Raydium framework ensures that tenant-specific configurations take precedence over global settings, allowing for granular control at the tenant level.</p><h3 id="environment-variables" tabindex="-1">Environment Variables <a class="header-anchor" href="#environment-variables" aria-label="Permalink to &quot;Environment Variables&quot;">​</a></h3><p>Tenants can also utilize environment variables defined in their <code>.env</code> files for sensitive information, ensuring that configuration files remain secure and version-controllable.</p><blockquote><p>Multi-tenancy in Raydium offers a powerful paradigm for managing multiple WordPress sites with efficiency and security. By providing each tenant with its own database and the ability to use custom configurations, Raydium ensures that tenants can enjoy a tailored, secure, and isolated environment.</p></blockquote>',24),r=[o];function s(c,l,d,h,u,m){return n(),a("div",null,r)}const b=e(i,[["render",s]]);export{p as __pageData,b as default};
