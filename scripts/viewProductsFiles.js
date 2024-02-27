export function convertToCSV(jsonData) {
    // Extract column headers from the first object in the array
    const headers = Object.keys(jsonData[0]);
    // Create CSV content with headers
    let csvContent = headers.join(',') + '\n';
    // Add rows to the CSV content
    jsonData.forEach(item => {
        const values = headers.map(header => {
            // Handle cases where values may contain commas or double quotes
            const escapedValue = item[header].toString().replace(/"/g, '""');
            return `"${escapedValue}"`;
        });
        csvContent += values.join(',') + '\n';
    });
    return csvContent;
}

export function downloadCSV(jsonData, fileName) {
    const csvContent = convertToCSV(jsonData);
    // Create a Blob object to store the CSV data
    const blob = new Blob([csvContent], {
        type: 'text/csv'
    });
    // Create a link element to trigger the download
    const a = document.createElement('a');
    a.href = window.URL.createObjectURL(blob);
    a.download = fileName;
    // Append the link to the body and trigger the download
    document.body.appendChild(a);
    a.click();
    // Cleanup
    document.body.removeChild(a);
    window.URL.revokeObjectURL(a.href);
}