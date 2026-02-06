                    <!-- 3rd Row: Calendar Component -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm rounded-3 p-3">
                                <h5 class="card-title fw-semibold mb-3">Repair Deadlines 📅</h5>
                                <div id="calendar" class="p-3"></div> <!-- Placeholder for the FullCalendar component -->
                            </div>
                        </div>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {

                            const rawData = @json($repairs);

                            // Convert object values to array
                            const eventsArray = Object.values(rawData);

                            const formattedEvents = eventsArray.map(item => ({
                                title: `${item.id}: ${item.device_type}\n${item.device}\n${item.status}`,
                                date: item.completion_date,
                                display: 'block' // prevents the default dot
                            }));

                            const calendarEl = document.getElementById('calendar');
                            const calendar = new FullCalendar.Calendar(calendarEl, {
                                initialView: 'dayGridMonth',
                                headerToolbar: {
                                    left: 'prev,next today',
                                    center: 'title',
                                    right: '' //dayGridMonth,timeGridWeek,timeGridDay
                                },
                                events: formattedEvents,

                                eventDidMount: function(info) {
                                    const [line1, line2, line3] = info.event.title.split('\n');

                                    // Status-based dynamic colors
                                    const statusColors = {
                                        // Primary Flow: Blue/Teal progression
                                        pending: '#f1c40f', // Yellow/Amber (Initial caution)
                                        accepted: '#3498db', // Bright Blue (Decision made, proceeding)

                                        // Confirmed and In Progress shifted towards blue/cyan/teal
                                        confirmed: '#3498db',
                                        in_progress: '#1CC5A9',

                                        completed: '#1bbd5eff',

                                        // Negative/Terminal Flow 
                                        declined: '#e74c3c', // Red (Client rejection)
                                        cancelled: '#c0392b', // Darker Red/Maroon (Repair halted)
                                    };

                                    const status = line3.trim();
                                    const color = statusColors[status] || '#4b0e0e';

                                    // Replace the event content with custom HTML
                                    info.el.innerHTML = `
                <div style="
                    white-space: normal;
                    line-height: 1.2em;
                    color: white;
                    background-color: ${color};
                    padding: 2px 4px;
                    border-radius: 3px;
                ">
                    <strong>${line1}</strong><br>
                    ${line2}<br>
                    ${line3}
                </div>
            `;

                                    // Remove default FullCalendar classes that add title/dot
                                    info.el.classList.remove('fc-event-title', 'fc-sticky', 'fc-daygrid-event-dot');
                                },

                                height: 'auto',
                                contentHeight: 'auto',
                                aspectRatio: 2,

                                datesSet: function() {
                                    // Style day headers dynamically
                                    const dayHeaders = calendarEl.querySelectorAll('.fc-col-header-cell-cushion');
                                    dayHeaders.forEach(el => {
                                        el.style.color = '#000';
                                        el.style.fontWeight = '600';
                                        el.style.padding = '5px';
                                    });

                                    // Change current day highlight color
                                    const todayCells = calendarEl.querySelectorAll('.fc-day-today');
                                    todayCells.forEach(cell => {
                                        cell.style.backgroundColor = 'rgba(0, 0, 0, 0.2)';
                                        cell.style.borderRadius = '0px';
                                        cell.style.color = '#000';
                                    });
                                }
                            });

                            calendar.render();
                        });
                    </script>