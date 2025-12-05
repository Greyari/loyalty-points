document.addEventListener("DOMContentLoaded", () => {
    const chartEl = document.getElementById("main-chart");
    let chartInstance = null;

    // ==========================
    // Helpers
    // ==========================
    const getBrandColor = () => {
        return getComputedStyle(document.documentElement)
            .getPropertyValue('--color-fg-brand')
            .trim() || "#1447E6";
    };

    const brandColor = getBrandColor();

    // ==========================
    // Base Chart Options
    // ==========================
    const baseOptions = {
        chart: {
            type: "area",
            height: 380,
            fontFamily: 'Poppins, sans-serif',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: false,
                    zoom: false,
                    zoomin: false,
                    zoomout: false,
                    pan: false,
                    reset: false
                }
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            },
            dropShadow: {
                enabled: true,
                top: 3,
                left: 0,
                blur: 4,
                opacity: 0.1,
            }
        },
        stroke: {
            width: 3,
            curve: 'smooth',
            lineCap: 'round'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: "vertical",
                shadeIntensity: 0.5,
                gradientToColors: ['#a78bfa', '#c4b5fd'],
                inverseColors: false,
                opacityFrom: 0.6,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        markers: {
            size: 0,
            colors: [brandColor],
            strokeColors: '#fff',
            strokeWidth: 2,
            hover: {
                size: 7,
                sizeOffset: 3
            }
        },
        grid: {
            show: true,
            borderColor: '#f1f1f1',
            strokeDashArray: 4,
            position: 'back',
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            padding: {
                top: 0,
                right: 10,
                bottom: 0,
                left: 10
            }
        },
        tooltip: {
            enabled: true,
            shared: true,
            followCursor: false,
            intersect: false,
            inverseOrder: false,
            custom: undefined,
            fillSeriesColor: false,
            theme: 'light',
            style: {
                fontSize: '13px',
                fontFamily: 'Poppins, sans-serif'
            },
            onDatasetHover: {
                highlightDataSeries: true,
            },
            x: {
                show: true,
                format: 'dd MMM',
            },
            y: {
                formatter: function(val) {
                    return val.toLocaleString() + " units"
                },
                title: {
                    formatter: (seriesName) => seriesName + ':'
                }
            },
            marker: {
                show: true,
            }
        },
        series: [],
        xaxis: {
            categories: [],
            labels: {
                style: {
                    colors: '#6B7280',
                    fontSize: '12px',
                    fontFamily: 'Poppins, sans-serif',
                    fontWeight: 500,
                },
                rotate: -45,
                rotateAlways: false,
                trim: false
            },
            axisBorder: {
                show: true,
                color: '#e5e7eb',
            },
            axisTicks: {
                show: true,
                color: '#e5e7eb',
            },
            tooltip: {
                enabled: false
            }
        },
        yaxis: {
            show: true,
            labels: {
                style: {
                    colors: '#6B7280',
                    fontSize: '12px',
                    fontFamily: 'Poppins, sans-serif',
                    fontWeight: 500,
                },
                formatter: val => Math.round(val).toLocaleString()
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            }
        },
        legend: {
            show: false
        }
    };

    // ==========================
    // LOAD CHART DATA FUNCTION
    // ==========================
    function loadChartData() {
        const year = document.getElementById("filterYear")?.value || "";
        const month = document.getElementById("filterMonth")?.value || "";
        const loader = document.getElementById("chartLoader");

        const params = new URLSearchParams();
        if (year) params.append('year', year);
        if (month) params.append('month', month);

        const url = `/chart-data?${params.toString()}`;

        // Show loader
        if (loader) loader.classList.remove('hidden');

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                const chartOptions = {
                    ...baseOptions,
                    series: [
                        {
                            name: "Sales Quantity",
                            data: data.qty,
                            color: brandColor
                        }
                    ],
                    xaxis: {
                        ...baseOptions.xaxis,
                        categories: data.categories
                    }
                };

                if (chartInstance) {
                    chartInstance.destroy();
                }

                chartInstance = new ApexCharts(chartEl, chartOptions);
                chartInstance.render();

                // Hide loader after render
                setTimeout(() => {
                    if (loader) loader.classList.add('hidden');
                }, 500);
            })
            .catch(err => {
                console.error("Chart Load Error: ", err);
                if (loader) loader.classList.add('hidden');

                // Show error message in a more user-friendly way
                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mt-4';
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm text-red-700 font-medium">Failed to load chart data. Please try again.</p>
                    </div>
                `;
                chartEl?.insertAdjacentElement('afterend', errorDiv);

                setTimeout(() => errorDiv.remove(), 5000);
            });
    }

    // ==========================
    // LOAD YEAR LIST FOR DROPDOWN
    // ==========================
    function loadYearOptions() {
        fetch('/chart-data-years')
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                const yearDropdown = document.getElementById("filterYear");

                if (!yearDropdown) return;

                // Clear existing options except the first one
                while (yearDropdown.options.length > 1) {
                    yearDropdown.remove(1);
                }

                data.years.forEach(year => {
                    const opt = document.createElement("option");
                    opt.value = year;
                    opt.textContent = year;
                    yearDropdown.appendChild(opt);
                });
            })
            .catch(err => {
                console.error("Year Load Error: ", err);
            });
    }

    // ==========================
    // EVENT LISTENER DROPDOWN
    // ==========================
    const yearFilter = document.getElementById("filterYear");
    const monthFilter = document.getElementById("filterMonth");

    if (yearFilter) {
        yearFilter.addEventListener("change", loadChartData);
    }

    if (monthFilter) {
        monthFilter.addEventListener("change", loadChartData);
    }

    // ==========================
    // FIRST LOAD
    // ==========================
    if (chartEl) {
        loadYearOptions();
        loadChartData();
    }
}); 
