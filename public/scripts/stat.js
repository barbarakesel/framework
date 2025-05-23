function fetchDataAndRender(field) {
    fetch(`/count/${field}`)
        .then(response => response.json())
        .then(data => {
            const labels = data.map(item => item.label);
            const counts = data.map(item => item.count);

            new Chart(document.getElementById('stat'), {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: `Count by ${field}`,
                        data: counts,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)'
                    }]
                }
            });
        });
}