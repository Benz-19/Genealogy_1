let currentPath = [];

async function fetchGenealogyData() {
    try {
        const response = await fetch('/genealogy/api/genealogy/tree');
        const rawText = await response.text(); // Get raw response
        
        try {
            const data = JSON.parse(rawText); // Try parsing manually
            currentPath = [data];
            render();
        } catch (parseError) {
            console.error("Malformed JSON received:", rawText); // This shows the "Route:..." culprit
            throw new Error('Server sent invalid data format');
        }
    } catch (error) {
        document.getElementById('tree-display').innerHTML = 
            `<div class="empty-msg" style="color: red;">Error: ${error.message}</div>`;
    }
}

function render() {
    if (currentPath.length === 0) return;

    const currentRoot = currentPath[currentPath.length - 1];
    
    // Breadcrumbs
    const bc = document.getElementById('breadcrumb-trail');
    bc.innerHTML = currentPath.map((node, index) => {
        const displayName = index === 0 ? `${node.name} (You)` : node.name;
        return `<span class="crumb" onclick="goToPath(${index})">${displayName}</span>`;
    }).join('<span class="separator">/</span>');

    // Main Display
    const display = document.getElementById('tree-display');
    display.innerHTML = `
        <div class="node">
            <strong>${currentPath.length === 1 ? currentRoot.name + ' (You)' : currentRoot.name}</strong>
            <div class="meta">Network Size: ${currentRoot.network_size}</div>
        </div>
        <div class="children-grid">
            ${(currentRoot.children && currentRoot.children.length > 0) ? 
                currentRoot.children.map(child => `
                    <div class="node" onclick="drillDown(${child.id})">
                        ${child.name}
                        <div class="meta">Depth: ${child.depth}</div>
                        <div class="meta">Network Size: ${child.network_size}</div>
                    </div>
                `).join('') : 
                '<div class="empty-msg">No further downlines</div>'
            }
        </div>
    `;
}

function drillDown(id) {
    const currentRoot = currentPath[currentPath.length - 1];
    const nextNode = currentRoot.children.find(c => c.id === id);
    if (nextNode) {
        currentPath.push(nextNode);
        render();
    }
}

function goToPath(index) {
    currentPath = currentPath.slice(0, index + 1);
    render();
}

fetchGenealogyData();