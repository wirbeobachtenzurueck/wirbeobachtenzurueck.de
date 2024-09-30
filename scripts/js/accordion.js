const togglePanel = (panelId) => {
    const targetPanel = document.getElementById(panelId);
    const targetButton = document.querySelector(`button[onclick="togglePanel('${panelId}')"]`);

    const isPanelOpen = targetPanel.style.display === 'block';

    const allPanels = document.querySelectorAll('.faqpanel, .indexpanel');
    const allButtons = document.querySelectorAll('.accordion-index button');
    
    if (!isPanelOpen) {
        allPanels.forEach(panel => panel.style.display = 'none');
        allButtons.forEach(button => button.setAttribute('aria-expanded', 'false'));
    }

    targetPanel.style.display = isPanelOpen ? 'none' : 'block';
    targetButton.setAttribute('aria-expanded', isPanelOpen ? 'false' : 'true');
}