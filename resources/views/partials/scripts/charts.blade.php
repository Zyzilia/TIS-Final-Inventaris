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
                        label: 'Inbound',
                        data: monthlyEmptyData,
                        backgroundColor: (ctx) => ctx.dataIndex === activeMonthIndex ? '#7c3aed' : '#c4b5fd',
                        borderRadius: {topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0},
                        barPercentage: 0.7,
                        categoryPercentage: 0.75,
                    },
                    {
                        label: 'Outbound',
                        data: monthlySoldData,
                        backgroundColor: (ctx) => ctx.dataIndex === activeMonthIndex ? '#a78bfa' : '#ede9fe',
                        borderRadius: {topLeft: 6, topRight: 6, bottomLeft: 0, bottomRight: 0},
                        barPercentage: 0.7,
                        categoryPercentage: 0.75,
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
                                const val = context.raw;
                                const label = context.dataset.label;
                                return `${label}: ${val} Unit`;
                            }
                        }
                    }
                },
                scales: {
                    x: { stacked: false, grid: { display: false }, border: { display: false }, ticks: { color: '#9CA3AF', font: { size: 11, family: 'Inter' } } },
                    y: { stacked: false, display: false, grid: { display: false } }
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

function updateCharts(monthlyInData, monthlyOutData) {
    if (!window.barChartInstance) return;

    // Replace all monthly data with actual data
    for (let i = 0; i < 12; i++) {
        monthlySoldData[i] = monthlyOutData[i] || 0;
        monthlyEmptyData[i] = monthlyInData[i] || 0;
    }

    window.barChartInstance.data.datasets[0].data = monthlyEmptyData;
    window.barChartInstance.data.datasets[1].data = monthlySoldData;

    // Update regional data proportionally for each month
    for (let i = 0; i < 12; i++) {
        const out = monthlyOutData[i] || 0;
        regionMonthlyData[i] = [
            Math.round(out * 0.45),
            Math.round(out * 0.35),
            Math.round(out * 0.20)
        ];
    }

    window.barChartInstance.update();

    // Show current month by default
    const currentMonthIndex = new Date().getMonth();
    updateDoughnutForMonth(currentMonthIndex);
}

// Initial monthly data (will be replaced by actual data)
const monthlySoldData = new Array(12).fill(0);
const monthlyEmptyData = new Array(12).fill(0);
const regionMonthlyData = Array.from({length: 12}, () => [0, 0, 0]);

let activeMonthIndex = new Date().getMonth();

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
