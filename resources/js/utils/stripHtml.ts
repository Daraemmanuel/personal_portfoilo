/**
 * Strips HTML tags from a string and returns plain text
 */
export function stripHtml(html: string): string {
    if (!html) return '';
    
    // Create a temporary DOM element to parse HTML
    const tmp = document.createElement('div');
    tmp.innerHTML = html;
    
    // Return the text content (which strips all HTML tags)
    return tmp.textContent || tmp.innerText || '';
}

