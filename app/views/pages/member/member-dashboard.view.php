<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <!-- STYLESHEET -->
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/member-style.css?v=<?php echo time();?>" />
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/assets/css/components/dashboard.css?v=<?php echo time();?>" />
    <!-- ICONS -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- CHART.JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?php echo APP_NAME; ?></title>
  </head>
  <body>

    <section class="sidebar">
    <?php require APPROOT.'/views/components/member-sidebar.view.php' ?>
    </section>
    
    <main>
      
    <div class="title">
        
        <h1>Dashboard</h1>
        <div class="greeting">
          <?php require APPROOT.'/views/components/user-greeting.view.php' ?>
        </div>

      </div>

      <div class="dashboard-container">

        <div class="left-column">

          <div class="insights">

            <div class="insight-card card-1">
              <div class="upper">
                <i class="ph ph-users"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                  <p>Total Members</p>
                  <div class="progress">
                    <h1>20000</h1>
                    <div class="text-muted">
                      <small>Last 30 days</small>
                    </div>
                  </div>
              </div>
            </div>
           
            <div class="insight-card card-2">
              <div class="upper">
                <i class="ph ph-user-plus"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                  <p>New Members</p>
                  <div class="progress">
                    <h1>20000</h1>
                    <div class="text-muted">
                      <small>Last 30 days</small>
                    </div>
                  </div>
              </div>
            </div>

            <div class="insight-card card-3">
              <div class="upper">
                <i class="ph ph-chat-circle-text"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                  <p>Total Inquiries</p>
                  <div class="progress">
                    <h1>20000</h1>
                    <div class="text-muted">
                      <small>Last 30 days</small>
                    </div>
                  </div>
              </div>
            </div>

            <div class="insight-card card-4">
              <div class="upper">
                <i class="ph ph-calendar-check"></i>
                <div class="status-badge">
                  <span>+9.4%</span>
                </div>
              </div>
              <div class="lower">
                  <p>Total Event Attendees</p>
                  <div class="progress">
                    <h1>20000</h1>
                    <div class="text-muted">
                      <small>Last 30 days</small>
                    </div>
                  </div>
              </div>
            </div>

          </div>

          <div class="bar-chart">

            <div class="upper">

              <div class="upper-text">
                <h2>Member Attendance</h2>
                <p>Track the total number of member who attended</p>
              </div>

              <div class="period-select">
                <select id="time-period" name="time-period">
                  <option value="today">Today</option>
                  <option value="week">This Week</option>
                </select>
              </div>

            </div>
            <div class="lower">
                <canvas id="BarChart"></canvas>
            </div>
          </div>

        </div>

        <div class="right-column">

          <div class="doughnut-chart">
            <div class="upper-text">
                  <h2>Membership Types</h2>
                  <p>Membership plan distribution</p>
            </div> 
            <div class="lower">
                <canvas id="DoughnutChart"></canvas>
            </div>
          </div>

          <div class="recent-announcements">
            <?php require APPROOT.'/views/components/recent-announcements.view.php' ?>
          </div>

        </div>

      <div class="chart">
        <div class="chart-header">
          <h2>Bookings</h2>
        </div>
        <div class="chart-container">
          <table class="paymentHistoryTable">
              <thead>
                <tr>
                  <th>Date</th>
                  <th>Trainer's detail</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody></tbody>
          </table>
        </div>
      </div>
    </main>
    <!-- SCRIPT -->
    <script src="<?php echo URLROOT; ?>/assets/js/member/member-script.js?v=<?php echo time();?>"></script>
    <script>
      const dateToday = new Date().toISOString().split('T')[0];

      document.addEventListener("DOMContentLoaded", () =>{ 
        fetch(`<?php echo URLROOT; ?>/member/index/api`)
          .then(response => {
            console.log('Response Status:', response.status);
            return response.json();
          })
          .then(data =>{
            console.log('bookings:',data);
            markBookings(data.bookings);
          })
          .catch(error => console.error('Error fetching bookings details:', error));
      });

      function markBookings(bookings) {
        const tbody = document.querySelector(".paymentHistoryTable tbody");
        tbody.innerHTML = "";
           
        // Filter bookings for "booked" 
        const filteredBookings = bookings.filter(
          booking => (booking.status === 'booked') && 
            new Date(booking.booking_date).getTime() >= new Date(dateToday).getTime()
        )
        .sort((a, b) => {
          const dateA = new Date(a.booking_date);
          const dateB = new Date(b.booking_date);

          if (dateA.getTime() !== dateB.getTime()) {
            return dateA - dateB; // sort by date ascending
          }

          const timeA = convertTo24hrs(a.timeslot.split(" - ")[0]); // "09:00 AM"
          const timeB = convertTo24hrs(b.timeslot.split(" - ")[0]); // "11:00 AM"

          return timeA.getTime() - timeB.getTime(); 
        });

        filteredBookings.forEach(booking => {
          const tr = document.createElement("tr");
          tr.innerHTML = `
            <td>${booking.booking_date}</td>
            <td>${booking.trainer_name}</td>
            <td>${booking.timeslot}</td>
          `;
          tbody.appendChild(tr);
        });
      }

      function convertTo24hrs(time){
        const [hrMin, period] = time.trim().split(' '); //AM,PM
        let [hr, min] =hrMin.split(':');
        hr = parseInt(hr, 10);
        min = parseInt(min, 10);
        let hr24 = hr;
        if(period === 'PM' && hr24 < 12) {
          hr24 +=12;
        } else if (period === 'AM' && hr24 ===12) {
          hr24 = 0;
        }
        return new Date(1970, 0, 1, hr24, min);
      }
    </script>                          

    <script>
      function updateBarChart(attendanceData) {
    console.log('Attendance Data:', attendanceData); // Debugging data

    if (barChartInstance) {
        barChartInstance.destroy();
    }

    const ctxBarChart = document.getElementById('BarChart').getContext('2d');

    let labels = [];
    let attendanceCounts = [];

    // Process the attendance data from the backend
    if (attendanceData && attendanceData.attendance_by_hour) {
        labels = Array.from({ length: 19 }, (_, index) => `${index + 5} AM`); // Hour labels from 5 AM to 11 PM
        attendanceCounts = attendanceData.attendance_by_hour.map(item => item.attendance_count); // Extract counts
    }

    // Ensure attendanceCounts matches the labels length
    if (attendanceCounts.length === labels.length) {
        const dataBarChart = {
            labels: labels,
            datasets: [{
                label: 'Number of Members Attended',
                data: attendanceCounts,
                fill: true,
                borderColor: '#5f63f2',
                backgroundColor: 'rgba(95, 99, 242, 0.2)',
                tension: 0.4,
                pointBackgroundColor: '#5f63f2',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 2,
                borderRadius: 10,
                barThickness: 50
            }]
        };

        const configBarChart = {
            type: 'bar',
            data: dataBarChart,
            options: {
                responsive: true,
                maintainAspectRatio: true,
                animation: {
                    duration: 2000,
                    easing: 'easeOutQuart',
                    onComplete: () => {
                        delayed = true;
                    },
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default' && !delayed) {
                            delay = context.dataIndex * 150 + context.datasetIndex * 50;
                        }
                        return delay;
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false,
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            stepSize: 10
                        },
                        stacked: true
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false
                        },
                        ticks: {
                            font: {
                                family: "'Poppins', sans-serif"
                            }
                        },
                        stacked: true
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 20,
                            font: {
                                family: "'Poppins', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        titleFont: {
                            family: "'Poppins', sans-serif"
                        },
                        bodyFont: {
                            family: "'Poppins', sans-serif"
                        }
                    }
                }
            }
        };

        barChartInstance = new Chart(ctxBarChart, configBarChart);
    } else {
        console.error('Invalid data structure for chart');
    }
}

    </script>

    <script>
      const ctxDoughnutChart = document.getElementById('DoughnutChart').getContext('2d');

      const dataDoughnutChart = {
        labels: ['Basic', 'Standard', 'Premium'],
        datasets: [{
          label: 'Membership Types',
          data: [30, 50, 20], // Replace with your actual data
          backgroundColor: [
            '#5f63f2',
            '#ff6384',
            '#36a2eb'
          ],
          hoverOffset: 4
        }]
      };

      const configDoughnutChart = {
        type: 'doughnut',
        data: dataDoughnutChart,
        options: {
          responsive: true,
          maintainAspectRatio: true,
          plugins: {
            legend: {
              position: 'top',
              labels: {
                boxWidth: 20,
                font: {
                  family: "'Poppins', sans-serif"
                }
              }
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.7)',
              padding: 10,
              titleFont: {
                family: "'Poppins', sans-serif"
              },
              bodyFont: {
                family: "'Poppins', sans-serif"
              }
            }
          }
        }
      };

      const doughnutChart = new Chart(ctxDoughnutChart, configDoughnutChart);
      </script>
  </body>
</html>

