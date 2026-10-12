import re
import sys

filepath = r"w:\home\cris\dev\breedersmap\resources\js\Layouts\AppLayout.vue"

with open(filepath, "r", encoding="utf-8") as f:
    content = f.read()

# Replace <nav ...> up to <!-- Page Content --> with nothing, as we'll wrap it in SidebarLayout
nav_start = content.find("<nav")
page_content_idx = content.find("<!-- Page Content -->")

if nav_start == -1 or page_content_idx == -1:
    print("Could not find nav_start or page_content")
    sys.exit(1)

nav_content = content[nav_start:page_content_idx].strip()

# Now find the SidebarLayout block
sidebar_start = content.find("<sidebar-layout>", page_content_idx)
sidebar_end = content.find("</sidebar-layout>", sidebar_start) + len("</sidebar-layout>")

if sidebar_start == -1 or sidebar_end == -1:
    print("Could not find sidebar")
    sys.exit(1)

new_sidebar_block = f"""        <sidebar-layout :services="services">
            <template #topbar>
                {nav_content}
            </template>
            <template #content>
                <div class="w-full flex-1 mx-auto max-w-full lg:max-w-[1920px]">
                    <slot />
                </div>
            </template>
        </sidebar-layout>"""

new_content = content[:nav_start] + new_sidebar_block + content[sidebar_end:]

with open(filepath, "w", encoding="utf-8") as f:
    f.write(new_content)

print("AppLayout successfully updated!")
