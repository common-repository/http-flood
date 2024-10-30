=== HTTP Flood ===
Contributors: aydinantmen
Donate link: https://www.ofis46.com/
Tags: syn flood, http flood, land flood, brute force, form spoofing, remote site explorer, attack, hack, hacking, ddos, security, vulnerability
Requires at least: 4.9
Tested up to: 4.9
Stable tag: 4.9
Requires PHP: 5.6
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==

HTTP Flood prevents your system against attacks like HTTP Flood, Land Flood, Form Spoofing, Brute Force, Remote Site Scanners and many more on similar types. It was tested under limitless thread and distributed sources.

Please Attention!
1. This plugin blocks every flood like actions.
2. So don't forget to deactivate the plugin before you do any fast moves e.g. sample data uploading...
3. If you banned your own site, you can re-enter only via change your ip address.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/http-flood` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. It's done.


== Frequently Asked Questions ==

= Is HTTP Flood plugin protected me againts DDoS? =

No. DDoS attacks are made on the server layer used server's ip address. HTTP Flood plugin running in software layer. So we could not detected and protection.

= So, protect me against what kind of attack? =

The attack sources arranged on the domain name, not on the server IP address, can be detected by the softwares. So we can detected and protected against them. These are: HTTP Flood, Land Flood, Form Spoofing, Brute Force and Remote Site Vulnerability Scanners.

= How it's work =

This plugin based on a detection algorithm, it's writes the attack resources to the .htaccess file. This file is a system file and tells resource must be blocked in a firewall layer to the server. HTTP Flood plugin detects the attack resources and writes them in .htaccess file at the time of attack.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* First stable version

== Upgrade Notice ==

= 1.0 =
This is first version. No need to upgrade.

== Arbitrary section ==

1- HTTP Connection Principle

Before connecting to a system running in the HTTP protocol, a data stream is generated between the server and the client called three way handshake.
Let's first examine how a normal connection is established.

SYN
The client sends a SYN (Synchronize) packet that indicates that we want to connect to the server. The client and server must be compatible in order to establish a connection. The SYN package carries information about the client structure.

SYN + ACK
When the server receives the SYN packet, it analyzes the structure of the client and starts to wait for the client's response by sending a SYN packet carrying information about the acknowledgment packet and the server structure, indicating an ACK (link acknowledgment).

ACK
When the client receives the SYN + ACK packet, it analyzes the structure of the server and sends a final ACK packet indicating that it satisfies the conditions required to establish the connection, and the data transfer starts by establishing a connection between the server and the client.

DATA TRANSFER

2- HTTP Flood Attack

So far we have examined the establishment of a normal HTTP connection.
Now let's examine how the HTTP Flood attack works.

SYN
The client requests a connection by sending a SYN packet as if it were a normal connection request.

SYN + ACK
The server sends the SYN + ACK packet as if it were a regular connection request and waits for the client's response.

SYN + 1
The client does not send the last ACK packet and the process repeats with a new SYN while the server is waiting for a response.

3- Protection Principle
We have seen the principle of normal connection and how these principles are manipulated to attack.
Finally, see how we provide security.

PERCEPTION
HTTP floods and derivative attacks occur at the application layer, not at the server layer like DDOS. We have developed a special algorithm that detects the attack parameters by examining the connection parameters.

DISCRIMINATION
Normal connection requests may continue to come in while the attack is ongoing, and a normal server should not remain unresponsive here. Our software distinguishes between attack requests and normal connection requests at this point.

BLOCK
The attack is detected in seconds and the attack source is notified to the server layer when the server is still able to respond to new connection requests. In this case, the attack source is blocked at the server layer and the server is not attacked.