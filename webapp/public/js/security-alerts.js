document.addEventListener('DOMContentLoaded', function() {
    console.log('Security alerts script loaded');
    
    // checking kung may security alerts every 10 s
    setInterval(checkForSecurityAlerts, 10000);
    
    checkForSecurityAlerts();
    
    function checkForSecurityAlerts() {
        console.log('Checking for security alerts...');
        
        fetch('/check-security-alerts')
            .then(response => {
                if (!response.ok) {
                    throw new Error(`${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw e;
                    }
                });
            })
            .then(data => {
                console.log('Security alerts data:', data);
                
                if (data.count > 0) {
                    console.log(`Found ${data.count} security alerts`);
                    showSecurityAlertModal(data.alerts);
                } else {
                    console.log('No security alerts found');
                }
            })
            .catch(error => console.error(error));
    }
    
    function showSecurityAlertModal(alerts) {
        if (document.getElementById('security-alert-modal')) {
            return;
        }
        
        const modal = document.createElement('div');
        modal.id = 'security-alert-modal';
        modal.className = 'fixed inset-0 z-50 flex items-center justify-center';
        modal.innerHTML = `
            <div class="fixed inset-0 bg-black opacity-50"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-3xl w-full mx-4">
                <div class="p-6">
                    <div class="flex items-center justify-between border-b border-red-500 pb-3 mb-4">
                        <div class="flex items-center">
                            <h3 class="text-xl font-bold text-red-600 dark:text-red-400">
                                Alert!
                            </h3>
                        </div>
                        <button id="close-security-modal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                            X
                        </button>
                    </div>
                    
                    <div id="security-alerts-container" class="mt-4 max-h-96 overflow-y-auto"></div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button id="acknowledge-security-alert" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 focus:outline-none">
                            Acknowledge
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        document.getElementById('close-security-modal').addEventListener('click', function() {
            modal.remove();
        });
        document.getElementById('acknowledge-security-alert').addEventListener('click', function() {
            modal.remove();
        });
        const alertsContainer = document.getElementById('security-alerts-container');
        alerts.forEach(alert => {
            const alertTime = new Date(alert.date_time_scanned || alert.timestamp);
            const alertElement = document.createElement('div');
            alertElement.className = 'mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-700 rounded-lg';
            
            alertElement.innerHTML = `
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <p class="text-lg font-bold text-gray-700">
                            Plate Number: ${alert.plate_number}
                        </p>
                        <p class="text-sm text-gray-700">
                            Location: ${alert.location || 'Main Entrance'}
                        </p>
                        <p class="text-sm font-medium text-gray-700">
                            Unauthorized Driver: ${alert.face_name || 'Unknown Person'}
                        </p>
                        <p class="text-sm text-gray-700 mt-1">
                            ${alertTime.toLocaleTimeString()} | ${alertTime.toLocaleDateString()}
                        </p>
                    </div>
                </div>
            `;
            
            alertsContainer.appendChild(alertElement);
        });
    }
});