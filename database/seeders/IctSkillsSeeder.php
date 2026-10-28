<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class IctSkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // =================================================================
            // A. IT Support & Systems Administration
            // =================================================================
            [
                'name' => 'IT support and troubleshooting',
                'category' => 'IT Support & Systems Administration',
                'proficiency' => 80,
                'description' => 'End-user technical triage, diagnostic workflows, software conflict resolution, and hardware issue escalation.',
                'tags' => 'helpdesk, troubleshooting, diagnostics, hardware, support',
                'experience_badge' => null,
            ],
            [
                'name' => 'Hardware and software troubleshooting',
                'category' => 'IT Support & Systems Administration',
                'proficiency' => 80,
                'description' => 'Component-level diagnostics, PC assembly, peripheral configuration, driver conflicts, and operating system recovery.',
                'tags' => 'hardware, pc-repair, diagnostics, peripherals, drivers',
                'experience_badge' => null,
            ],
            [
                'name' => 'Windows operating systems',
                'category' => 'IT Support & Systems Administration',
                'proficiency' => 80,
                'description' => 'Windows 10/11 environment administration, local group policy configuration, registry maintenance, and management tools.',
                'tags' => 'windows, sysadmin, os, configuration, desktop',
                'experience_badge' => null,
            ],
            [
                'name' => 'Linux administration',
                'category' => 'IT Support & Systems Administration',
                'proficiency' => 75,
                'description' => 'Command-line system administration, user accounts, systemd service management, package managers, and permissions.',
                'tags' => 'linux, bash, systemd, permissions, cli',
                'experience_badge' => null,
            ],
            [
                'name' => 'User account and access management',
                'category' => 'IT Support & Systems Administration',
                'proficiency' => 75,
                'description' => 'User onboarding, credential lifecycle, role-based access control (RBAC), and directory permissions management.',
                'tags' => 'iam, access-control, rbac, credentials, security',
                'experience_badge' => null,
            ],
            [
                'name' => 'System maintenance and updates',
                'category' => 'IT Support & Systems Administration',
                'proficiency' => 75,
                'description' => 'OS security patching, scheduled maintenance routines, disk health checks, and routine system optimization.',
                'tags' => 'maintenance, patches, updates, system-health',
                'experience_badge' => null,
            ],
            [
                'name' => 'Helpdesk and technical support',
                'category' => 'IT Support & Systems Administration',
                'proficiency' => 80,
                'description' => 'Multi-channel IT support ticketing, remote desktop troubleshooting, ticket prioritization, and user satisfaction.',
                'tags' => 'helpdesk, remote-support, triage, customer-service',
                'experience_badge' => null,
            ],
            [
                'name' => 'Technical documentation',
                'category' => 'IT Support & Systems Administration',
                'proficiency' => 80,
                'description' => 'Authoring standard operating procedures (SOPs), user training guides, and system troubleshooting runbooks.',
                'tags' => 'documentation, sops, runbooks, technical-writing',
                'experience_badge' => null,
            ],
            [
                'name' => 'Basic server administration',
                'category' => 'IT Support & Systems Administration',
                'proficiency' => 70,
                'description' => 'Foundational server provisioning, background service uptime monitoring, daemon health checks, and log review.',
                'tags' => 'sysadmin, servers, monitoring, uptime',
                'experience_badge' => null,
            ],

            // =================================================================
            // B. Networking & Infrastructure
            // =================================================================
            [
                'name' => 'TCP/IP networking',
                'category' => 'Networking & Infrastructure',
                'proficiency' => 75,
                'description' => 'IPv4/IPv6 addressing, CIDR subnetting, TCP handshake, UDP transport, and OSI reference model architecture.',
                'tags' => 'tcp-ip, networking, subnetting, osi-model',
                'experience_badge' => null,
            ],
            [
                'name' => 'DNS and DHCP fundamentals',
                'category' => 'Networking & Infrastructure',
                'proficiency' => 80,
                'description' => 'Domain resolution mechanics, A/CNAME/MX DNS records, nameserver management, and dynamic DHCP address allocation.',
                'tags' => 'dns, dhcp, nameservers, ip-allocation',
                'experience_badge' => null,
            ],
            [
                'name' => 'LAN configuration and troubleshooting',
                'category' => 'Networking & Infrastructure',
                'proficiency' => 75,
                'description' => 'Local area network deployment, structured ethernet cabling, static/dynamic IP settings, and connectivity verification.',
                'tags' => 'lan, ethernet, ip-config, connectivity',
                'experience_badge' => null,
            ],
            [
                'name' => 'Routing and switching fundamentals',
                'category' => 'Networking & Infrastructure',
                'proficiency' => 70,
                'description' => 'Router configuration, switch port VLAN segmentation, default gateways, and packet forwarding mechanics.',
                'tags' => 'routing, switching, vlans, gateways',
                'experience_badge' => null,
            ],
            [
                'name' => 'Network connectivity troubleshooting',
                'category' => 'Networking & Infrastructure',
                'proficiency' => 75,
                'description' => 'Physical-to-application layer diagnostics utilizing ping, traceroute, nslookup, netstat, and cable testers.',
                'tags' => 'diagnostics, troubleshooting, traceroute, ping',
                'experience_badge' => null,
            ],
            [
                'name' => 'Network security fundamentals',
                'category' => 'Networking & Infrastructure',
                'proficiency' => 75,
                'description' => 'Network perimeter defense, firewall access control lists (ACLs), port security, and secure network segmentation.',
                'tags' => 'network-security, acls, firewalls, segmentation, protocols',
                'experience_badge' => null,
            ],
            [
                'name' => 'Huawei networking fundamentals',
                'category' => 'Networking & Infrastructure',
                'proficiency' => 70,
                'description' => 'HCIA-level routing/switching concepts, VRP command-line navigation, and enterprise topology configuration.',
                'tags' => 'huawei, hcia, vrp, networking, enterprise',
                'experience_badge' => null,
            ],
            [
                'name' => 'Network infrastructure configuration',
                'category' => 'Networking & Infrastructure',
                'proficiency' => 70,
                'description' => 'Hardware placement, patch panel termination, wireless access point provisioning, and network topology design.',
                'tags' => 'infrastructure, topology, access-points, hardware',
                'experience_badge' => null,
            ],

            // =================================================================
            // C. Cybersecurity
            // =================================================================
            [
                'name' => 'Secure system configuration',
                'category' => 'Cybersecurity',
                'proficiency' => 75,
                'description' => 'Operating system hardening, disabling unnecessary services, host firewall configuration (UFW/iptables), and audit logging.',
                'tags' => 'hardening, firewalls, security, ufw, compliance',
                'experience_badge' => null,
            ],
            [
                'name' => 'Access control and permissions',
                'category' => 'Cybersecurity',
                'proficiency' => 80,
                'description' => 'Enforcing principle of least privilege, Linux file permission bits (chmod/chown), and secure sudoers delegation.',
                'tags' => 'permissions, access-control, sudoers, least-privilege',
                'experience_badge' => null,
            ],
            [
                'name' => 'Web application security fundamentals',
                'category' => 'Cybersecurity',
                'proficiency' => 80,
                'description' => 'OWASP Top 10 awareness, input sanitization against XSS, SQL injection defenses, and secure HTTP security headers.',
                'tags' => 'owasp, web-security, xss, sqli, defense',
                'experience_badge' => null,
            ],
            [
                'name' => 'Security testing in authorized lab environments',
                'category' => 'Cybersecurity',
                'proficiency' => 70,
                'description' => 'Hands-on vulnerability testing in controlled lab environments (PortSwigger Web Security Academy, TryHackMe, local VMs).',
                'tags' => 'lab-testing, ctf, authorized-testing, portswigger',
                'experience_badge' => null,
            ],
            [
                'name' => 'Basic security monitoring and incident awareness',
                'category' => 'Cybersecurity',
                'proficiency' => 70,
                'description' => 'Log analysis, authentication failure inspection, brute-force pattern awareness, and incident escalation protocols.',
                'tags' => 'monitoring, logs, incident-response, alerts',
                'experience_badge' => null,
            ],
            [
                'name' => 'Common web security risks and mitigation',
                'category' => 'Cybersecurity',
                'proficiency' => 75,
                'description' => 'Defensive mitigation for CSRF, insecure direct object references (IDOR), sensitive data exposure, and session hijacking.',
                'tags' => 'web-risks, mitigation, session-security, idor',
                'experience_badge' => null,
            ],

            // =================================================================
            // D. Cloud, DevOps & Deployment
            // =================================================================
            [
                'name' => 'AWS EC2 fundamentals',
                'category' => 'Cloud, DevOps & Deployment',
                'proficiency' => 70,
                'description' => 'Elastic Compute Cloud instance provisioning, Linux AMI configuration, key pairs, and security group firewall rules.',
                'tags' => 'aws, ec2, cloud, compute, linux',
                'experience_badge' => null,
            ],
            [
                'name' => 'AWS S3 fundamentals',
                'category' => 'Cloud, DevOps & Deployment',
                'proficiency' => 70,
                'description' => 'Simple Storage Service bucket creation, public access block policies, object storage, and static asset delivery.',
                'tags' => 'aws, s3, storage, buckets, assets',
                'experience_badge' => null,
            ],
            [
                'name' => 'AWS IAM fundamentals',
                'category' => 'Cloud, DevOps & Deployment',
                'proficiency' => 70,
                'description' => 'IAM users, groups, roles, policy attachment, least privilege permission boundaries, and programmatic access keys.',
                'tags' => 'aws, iam, security, policies, cloud-credentials',
                'experience_badge' => null,
            ],
            [
                'name' => 'Docker and container management',
                'category' => 'Cloud, DevOps & Deployment',
                'proficiency' => 75,
                'description' => 'Container lifecycle management, Dockerfile definition, multi-container networking concepts, and image builds.',
                'tags' => 'docker, containers, devops, virtualization',
                'experience_badge' => null,
            ],
            [
                'name' => 'GitHub Actions and CI/CD fundamentals',
                'category' => 'Cloud, DevOps & Deployment',
                'proficiency' => 75,
                'description' => 'Automated GitHub Actions workflows, test execution runners, linter checks, and automated continuous deployment.',
                'tags' => 'github-actions, ci-cd, automation, testing',
                'experience_badge' => null,
            ],
            [
                'name' => 'Linux-based deployment',
                'category' => 'Cloud, DevOps & Deployment',
                'proficiency' => 80,
                'description' => 'Production deployment on Linux VPS, Nginx reverse proxy configuration, systemd process management, and permissions.',
                'tags' => 'linux, deployment, nginx, systemd, devops',
                'experience_badge' => null,
            ],
            [
                'name' => 'Web hosting and domain configuration',
                'category' => 'Cloud, DevOps & Deployment',
                'proficiency' => 85,
                'description' => 'Registrar DNS mapping, cPanel/Apache virtual host management, subdomains, and production web asset routing.',
                'tags' => 'hosting, domains, dns, vhosts, web-servers',
                'experience_badge' => null,
            ],
            [
                'name' => 'SSL/TLS and HTTPS configuration',
                'category' => 'Cloud, DevOps & Deployment',
                'proficiency' => 85,
                'description' => 'Let\'s Encrypt Certbot SSL certificate issuance, automated renewal hooks, and strict HTTPS redirection.',
                'tags' => 'ssl, tls, https, certbot, security',
                'experience_badge' => null,
            ],
            [
                'name' => 'Application deployment troubleshooting',
                'category' => 'Cloud, DevOps & Deployment',
                'proficiency' => 85,
                'description' => 'Diagnosing 500/502/504 Bad Gateway errors, PHP-FPM socket issues, environment variable mismatches, and file permission triage.',
                'tags' => 'troubleshooting, diagnostics, logs, deployment, 502-error',
                'experience_badge' => null,
            ],

            // =================================================================
            // E. Database & Technical Operations
            // =================================================================
            [
                'name' => 'SQL querying',
                'category' => 'Database & Technical Operations',
                'proficiency' => 85,
                'description' => 'Multi-table relational queries, complex JOINs, aggregation functions, indexing efficiency, and data extraction.',
                'tags' => 'sql, querying, joins, indexing, data',
                'experience_badge' => null,
            ],
            [
                'name' => 'Database administration fundamentals',
                'category' => 'Database & Technical Operations',
                'proficiency' => 75,
                'description' => 'MySQL user grant management, privilege separation, database character sets/collations, and InnoDB storage engine concepts.',
                'tags' => 'dba, administration, privileges, innodb',
                'experience_badge' => null,
            ],
            [
                'name' => 'phpMyAdmin',
                'category' => 'Database & Technical Operations',
                'proficiency' => 85,
                'description' => 'Web-based database administration, table management, schema exports/imports, raw SQL queries, and user privilege setup.',
                'tags' => 'phpmyadmin, mysql, gui, export, management',
                'experience_badge' => null,
            ],
            [
                'name' => 'Database backup and restoration',
                'category' => 'Database & Technical Operations',
                'proficiency' => 85,
                'description' => 'Executing mysqldump exports, automated scheduled SQL backups, compressed archiving, and data disaster recovery imports.',
                'tags' => 'backup, restore, mysqldump, disaster-recovery',
                'experience_badge' => null,
            ],
            [
                'name' => 'Application environment configuration',
                'category' => 'Database & Technical Operations',
                'proficiency' => 85,
                'description' => 'Managing .env environment variables, database credential isolation, runtime configuration caching, and production secrets hygiene.',
                'tags' => 'configuration, env, secrets, devops, production',
                'experience_badge' => null,
            ],
            [
                'name' => 'Production troubleshooting and diagnostics',
                'category' => 'Database & Technical Operations',
                'proficiency' => 85,
                'description' => 'Live application log inspection, Artisan CLI troubleshooting, service restart procedures, and error stack-trace resolution.',
                'tags' => 'production, diagnostics, troubleshooting, logs, artisan',
                'experience_badge' => null,
            ],
        ];

        $now = now();

        foreach ($skills as $skillData) {
            // Check if a skill with the same name already exists to prevent duplicate insertion
            $existing = Skill::where('name', $skillData['name'])->first();

            if (!$existing) {
                Skill::create(array_merge($skillData, [
                    'is_published' => true,
                    'created_at' => $now,
                ]));
            }
        }
    }
}
