// Charts initialization
window.barChartInstance = null;
window.doughnutChartInstance = null;

function initCharts() {
    const ctxBar = document.getElementById('barChart');
    if (ctxBar) {
        window.barChartInstance = new Chart(ctxBar.getContext('2d'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [
                    {
                        label: 'Sold',
                        data: monthlySoldData,
                        backgroundColor: (ctx) => ctx.dataIndex === activeMonthIndex ? '#9A82EA' : '#D3C6F9',
                        borderRadius: {bottomLeft: 6, bottomRight: 6, topLeft: 0, topRight: 0},
                        barPercentage: 0.5,
                        stack: 'Stack 0',
                    },
                    {
                        label: 'Remaining',
                        data: monthlyEmptyData,
                        backgroundColor: 'transparent',
                        borderColor: (ctx) => ctx.dataIndex === activeMonthIndex ? '#9A82EA' : '#D3C6F9',
                        borderWidth: {top: 1, right: 1, bottom: 0, left: 1},
                        borderDash: [3, 3],
                        borderRadius: {topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0},
                        barPercentage: 0.5,
                        stack: 'Stack 0',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                onClick: (event, elements) => {
                    if (elements.length > 0) {
                        const index = elements[0].index;
                        updateDoughnutForMonth(index);
                    }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#1B1C22',
                        titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 11 },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            title: function(tooltipItems) {
                                return `Bulan: ${tooltipItems[0].label}`;
                            },
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    const val = context.raw;
                                    if (val === 0) return 'Belum ada data penjualan';
                                    return `Total Terjual: ${val} Unit`;
                                }
                                return null;
                            }
                        }
                    }
                },
                scales: {
                    x: { stacked: true, grid: { display: false }, border: { display: false }, ticks: { color: '#9CA3AF', font: { size: 11, family: 'Inter' } } },
                    y: { stacked: true, display: false, grid: { display: false } }
                }
            }
        });
    }

    const ctxDoughnut = document.getElementById('doughnutChart');
    if (ctxDoughnut) {
        window.doughnutChartInstance = new Chart(ctxDoughnut.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['JKT', 'SBY', 'BDG'],
                datasets: [{
                    data: [45, 20, 18], // Default to May data
                    backgroundColor: ['#9A82EA', '#D3C6F9', '#1B1C22'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        enabled: true,
                        backgroundColor: '#1B1C22',
                        titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 11 },
                        padding: 12,
                        cornerRadius: 12,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                if (value === 0) return 'Tidak ada penjualan';
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((value / total) * 100).toFixed(0);
                                return ` Omset: Rp ${value}.000.000 (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '75%',
                layout: { padding: 10 }
            }
        });
        updateDoughnutForMonth(4);
    }
}

function updateCharts(inData, outData) {
    if (!window.barChartInstance) return;

    // Use actual data for the current month (June = index 5)
    const currentMonthIndex = 5;
    monthlySoldData[currentMonthIndex] = outData;
    monthlyEmptyData[currentMonthIndex] = inData;

    window.barChartInstance.data.datasets[0].data = monthlySoldData;
    window.barChartInstance.data.datasets[1].data = monthlyEmptyData;
    
    // Also update regional data for current month
    regionMonthlyData[currentMonthIndex] = [
        Math.round(outData * 0.45),
        Math.round(outData * 0.35),
        Math.round(outData * 0.20)
    ];

    window.barChartInstance.update();
    updateDoughnutForMonth(currentMonthIndex);
}

// Initial monthly data
const monthlySoldData = [40, 55, 45, 30, 45, 0, 0, 0, 0, 0, 0, 0];
const monthlyEmptyData = [20, 25, 20, 15, 20, 0, 0, 0, 0, 0, 0, 0];
const regionMonthlyData = [
    [40, 20, 15], // Jan
    [55, 30, 25], // Feb
    [45, 25, 20], // Mar
    [30, 15, 10], // Apr
    [45, 20, 18], // May
    [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0], [0, 0, 0]
];

let activeMonthIndex = 4; // May (index 4) is active by default

function updateDoughnutForMonth(index) {
    if (!window.doughnutChartInstance) return;
    
    activeMonthIndex = index;
    if(window.barChartInstance) window.barChartInstance.update();

    const data = regionMonthlyData[index];
    window.doughnutChartInstance.data.datasets[0].data = data;
    window.doughnutChartInstance.update();

    const total = data.reduce((a, b) => a + b, 0);
    document.getElementById('doughnut-pct-jkt').textContent = `${total > 0 ? Math.round((data[0]/total)*100) : 0}%`;
    document.getElementById('doughnut-pct-sby').textContent = `${total > 0 ? Math.round((data[1]/total)*100) : 0}%`;
    document.getElementById('doughnut-pct-bdg').textContent = `${total > 0 ? Math.round((data[2]/total)*100) : 0}%`;
}

// Call init on load
document.addEventListener('DOMContentLoaded', initCharts);
