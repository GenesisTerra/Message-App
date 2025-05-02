// Global variables
let isExpanded1 = JSON.parse(localStorage.getItem("isExpanded1") || "false");
let activeSidebar = localStorage.getItem("activeSidebar");
if (
    activeSidebar === "deleteChat" ||
    activeSidebar === "createChat" ||
    activeSidebar === "createGrp"
) {
    activeSidebar = "liveChat"; // Default sidebar
}
if (window.innerWidth <= 768) {
    activeSidebar = null; // Hide all sidebars on mobile
}
let activeChatTab = localStorage.getItem("activeChatTab") || "private";
let currentTab = 0;


function openModal(img) {
    let modal = document.getElementById("imageModal");
    let modalImg = document.getElementById("modalImage");
    modal.style.display = "flex"; 
    modalImg.src = img.src; 
    modalImg.onclick = (event) => event.stopPropagation();
}

function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}

function updateFileName() {
    let fileInput = document.getElementById("fileInput");
    let fileNameDisplay = document.getElementById("fileName");
    let removeButton = document.getElementById("removeFile");

    if (fileInput.files.length > 0) {
        fileNameDisplay.textContent = fileInput.files[0].name;
        removeButton.style.display = "inline-block"; // Show remove button
    } else {
        fileNameDisplay.textContent = "";
        removeButton.style.display = "none"; // Hide remove button
    }
}

function removeFile() {
    let fileInput = document.getElementById("fileInput");
    fileInput.value = ""; // Clear file input
    document.getElementById("fileName").textContent = ""; // Reset display
    document.getElementById("removeFile").style.display = "none"; // Hide remove button
}

// Theme functions
function toggleTheme() {
    document.body.classList.toggle("light-mode");
    const currentTheme = document.body.classList.contains("light-mode")
        ? "light"
        : "dark";
    localStorage.setItem("theme", currentTheme);
    updateThemeIcon();
}

function applySavedTheme() {
    document.body.classList.add('no-transition');
    const savedTheme = localStorage.getItem("theme");
    const systemPrefersLight = window.matchMedia(
        "(prefers-color-scheme: light)"
    ).matches;

    if (savedTheme === "light" || (!savedTheme && systemPrefersLight)) {
        document.body.classList.add("light-mode");
    } else {
        document.body.classList.remove("light-mode");
    }
    void document.body.offsetWidth;
    document.body.classList.remove('no-transition');
    
    updateThemeIcon();
}

function updateThemeIcon() {
    const themeButton = document.getElementById("themeToggle");
    if (!themeButton) return;

    if (document.body.classList.contains("light-mode")) {
        themeButton.innerHTML = "&#127774;"; // Sun emoji (🌞)
        themeButton.setAttribute("aria-label", "Switch to dark mode");
    } else {
        themeButton.innerHTML = "&#127769;"; // Moon emoji (🌚)
        themeButton.setAttribute("aria-label", "Switch to light mode");
    }
}

// Sidebar functions
function sidebartoggle() {
    let div1 = document.getElementById("div1");
    let texts = document.querySelectorAll("#div1 a span");
    isExpanded1 = !isExpanded1;
    texts.forEach(
        (text) => (text.style.display = isExpanded1 ? "inline" : "none")
    );
    let div1Span = isExpanded1 ? 3 : 1;
    div1.style.gridColumn = `span ${div1Span}`;
    document.getElementById("main").style.gridColumn = `span ${20 - div1Span}`;
    localStorage.setItem("isExpanded1", JSON.stringify(isExpanded1));
}

function restoreSidebarState() {
    let div1 = document.getElementById("div1");
    if (!div1) return;

    let texts = document.querySelectorAll("#div1 a span");
    texts.forEach(
        (text) => (text.style.display = isExpanded1 ? "inline" : "none")
    );
    let div1Span = isExpanded1 ? 3 : 1;
    div1.style.gridColumn = `span ${div1Span}`;
    document.getElementById("main").style.gridColumn = `span ${20 - div1Span}`;
}

// Sidebar navigation functions
function showSidebar(sidebarId) {
    let sidebars = ["liveChat", "createChat", "deleteChat", "createGrp"];

    activeSidebar = sidebarId;
    localStorage.setItem("activeSidebar", sidebarId);

    sidebars.forEach((id) => {
        let bar = document.getElementById(id);
        let activeElements = document.querySelectorAll(`.${id}_a`);

        if (bar) {
            let isActive = id === sidebarId;
            bar.style.display = isActive ? "block" : "none";
            activeElements.forEach((el) =>
                el.classList.toggle("active", isActive)
            );
        }
    });
    adjustChatMenu();
}

function toggleSidebar(sidebarId) {
    // If the same sidebar is clicked again, close it
    if (activeSidebar === sidebarId) {
        document.getElementById(sidebarId).style.display = "none";
        document
            .querySelectorAll(`.${sidebarId}_a`)
            .forEach((el) => el.classList.remove("active"));
        activeSidebar = null;
        localStorage.removeItem("activeSidebar");
        adjustChatMenu();
        return;
    } else {
        showSidebar(sidebarId);
    }
}

// Tab functions
function switchTab(tabType) {
    activeChatTab = tabType;

    // For chat list
    const chatList = document.getElementById("chatList");
    if (chatList) {
        chatList
            .querySelectorAll(".tab-btn")
            .forEach((btn) => btn.classList.remove("active"));
        if (tabType === "private") {
            chatList.querySelector(".private-btn").classList.add("active");
            chatList.querySelector(".private-chats").style.display = "block";
            chatList.querySelector(".group-chats").style.display = "none";
        } else {
            chatList.querySelector(".group-btn").classList.add("active");
            chatList.querySelector(".private-chats").style.display = "none";
            chatList.querySelector(".group-chats").style.display = "block";
        }
    }

    // For delete list
    const delList = document.getElementById("delList");
    if (delList) {
        delList
            .querySelectorAll(".tab-btn")
            .forEach((btn) => btn.classList.remove("active"));
        if (tabType === "private") {
            delList.querySelector(".private-btn").classList.add("active");
            delList.querySelector(".private-chats").style.display = "block";
            delList.querySelector(".group-chats").style.display = "none";
        } else {
            delList.querySelector(".group-btn").classList.add("active");
            delList.querySelector(".private-chats").style.display = "none";
            delList.querySelector(".group-chats").style.display = "block";
        }
    }

    localStorage.setItem("activeChatTab", tabType);
    // Trigger search update after tab switch
    const activeBar = document.querySelector(
        '.bar:not([style*="display: none"])'
    );
    if (activeBar) {
        const searchInput = activeBar.querySelector(".search-input");
        if (searchInput && searchInput.onchange) {
            searchInput.onchange();
        }
    }
}

// Form wizard functions
function showTab(n, id) {
    let tabs = document.querySelectorAll(".tab");
    if (tabs.length === 0) return;

    tabs.forEach((tab) => (tab.style.display = "none"));
    tabs[n].style.display = "block";

    const prevBtn = document.getElementById("prevBtn");
    const nextBtn = document.getElementById("nextBtn");

    if (prevBtn) prevBtn.style.display = n === 0 ? "none" : "inline";
    if (nextBtn) nextBtn.innerHTML = n === tabs.length - 1 ? id : "Next";

    document.querySelectorAll(".step").forEach((step, index) => {
        step.classList.toggle("active", index === n);
        step.classList.toggle("finish", index < n);
    });
}

function nextPrev(n, id) {
    let tabs = document.querySelectorAll(".tab");
    if (tabs.length === 0) return false;

    tabs[currentTab].style.display = "none";
    currentTab += n;

    if (currentTab >= tabs.length) {
        document.getElementById(id)?.submit();
        return false;
    }

    showTab(currentTab, id);
    return false;
}

function adjustChatMenu() {
    const chatMenu = document.getElementById("chat-menu");
    const otherDivs = [
        document.getElementById("liveChat"),
        document.getElementById("createChat"),
        document.getElementById("deleteChat"),
        document.getElementById("createGrp"),
    ];

    // Check if all other divs are hidden
    const allHidden = otherDivs.every(
        (div) =>
            div.style.display === "none" ||
            window.getComputedStyle(div).display === "none"
    );

    // Set grid column span directly
    chatMenu.style.gridColumn = allHidden ? "span 2" : "span 1";
}

// Search functionality for all interfaces
function handleSearch(searchInput, options = {}) {
    const searchTerm = searchInput.value.toLowerCase();
    const parentContainer = searchInput.closest(".bar");

    // Determine which elements to search based on options
    let itemsContainer;
    if (options.specificContainer) {
        itemsContainer = document.getElementById(options.specificContainer);
    } else {
        // For interfaces with tabs
        const activeSection = parentContainer.querySelector(
            '.chat-section:not([style*="display: none"])'
        );
        itemsContainer =
            activeSection || parentContainer.querySelector(".chat-section");
    }

    if (!itemsContainer) return;

    // Handle different item types (buttons or labels with checkboxes)
    const items = itemsContainer.querySelectorAll(
        options.checkboxMode ? ".user-item" : ".button-link"
    );

    items.forEach((item) => {
        const userName =
            item.querySelector("strong")?.textContent.toLowerCase() || "";
        const userInfo =
            item.querySelector("span")?.textContent.toLowerCase() || "";

        if (userName.includes(searchTerm) || userInfo.includes(searchTerm)) {
            item.style.display = "flex";
        } else {
            item.style.display = "none";
        }
    });
}

document.addEventListener("DOMContentLoaded", function () {
    try {
        let chatContainer = document.querySelector(".content-box > div:nth-child(2)");
        if (chatContainer) {
            setTimeout(() => {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }, 100);
        }
    } catch (e) {
        console.log("Error in chat scroll");
    }

    try {
        adjustChatMenu();
    } catch (e) {
        console.log("Error in adjustChatMenu");
    }

    try {
        applySavedTheme();
    } catch (e) {
        console.log("Error in applySavedTheme");
    }

    try {
        restoreSidebarState();
    } catch (e) {
        console.log("Error in restoreSidebarState");
    }

    try {
        const sidebarToggle = document.getElementById("sidebarToggle");
        if (sidebarToggle) {
            sidebarToggle.addEventListener("click", sidebartoggle);
        }
    } catch (e) {
        console.log("Error setting sidebarToggle event");
    }

    try {
        const themeToggle = document.getElementById("themeToggle");
        if (themeToggle) {
            themeToggle.addEventListener("click", toggleTheme);
        }
    } catch (e) {
        console.log("Error setting themeToggle event");
    }

    try {
        if (activeSidebar && activeSidebar !== "null") {
            showSidebar(activeSidebar);
        }
        switchTab(activeChatTab);
        showTab(currentTab, "Setup");
    } catch (e) {
        console.log("Error in initial UI state setup");
    }

    try {
        document
            .querySelectorAll("#chatList .tab-btn, #delList .tab-btn")
            .forEach((btn) => {
                btn.addEventListener("click", function () {
                    switchTab(this.classList.contains("private-btn") ? "private" : "group");
                });
            });
    } catch (e) {
        console.log("Error in chat tab switchers");
    }

    try {
        document.querySelectorAll("[data-sidebar]").forEach((link) => {
            link.addEventListener("click", function (e) {
                e.preventDefault();
                const sidebarId = this.getAttribute("data-sidebar");
                toggleSidebar(sidebarId);
            });
        });
    } catch (e) {
        console.log("Error in sidebar navigation");
    }

    try {
        document
            .querySelectorAll('.bar-controls a[onclick^="toggleSidebar"]')
            .forEach((closeBtn) => {
                closeBtn.addEventListener("click", function () {
                    const sidebarId = this.getAttribute("onclick").match(/'([^']+)'/)[1];
                    const sidebar = document.getElementById(sidebarId);
                    const searchInput = sidebar.querySelector(".search-input");
                    if (searchInput) {
                        searchInput.value = "";
                        if (searchInput.onchange) {
                            searchInput.onchange();
                        }
                    }
                });
            });
    } catch (e) {
        console.log("Error in clear search input logic");
    }
});

// Responsive behavior
window.addEventListener("resize", function () {
    if (window.innerWidth <= 768) {
        // Hide all sidebars on mobile
        document.querySelectorAll(".bar").forEach((bar) => {
            bar.style.display = "none";
        });
        activeSidebar = null;
        localStorage.removeItem("activeSidebar");
    } else {
        // Restore sidebar state on desktop
        if (activeSidebar) {
            showSidebar(activeSidebar);
        }
    }
    adjustChatMenu();
});