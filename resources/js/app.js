import './bootstrap';


import Alpine from 'alpinejs';


window.Alpine = Alpine;


window.userValidation = function () {
    return {
        fields: {
            first_name: '',
            last_name: '',
            phone_number: '',
            birthdate: '',
            code: '',
            note: '',
            email: '',
            password: '',
            password_confirmation: ''
        },
        errors: {
            first_name: '',
            last_name: '',
            phone_number: '',
            birthdate: '',
            code: '',
            note: '',
            email: '',
            password: '',
            password_confirmation: ''
        },
        regex: {
            first_name: /^[A-Za-zÀ-Ü]+(?:[-\'][A-Za-zÀ-Ü]+)*$/,
            last_name: /^[A-Za-zÀ-Ü]+(?:[-\'][A-Za-zÀ-Ü]+)*$/,
            phone_number: /^\(\d{3}\) \d{3}-\d{4}$/,
            code: /^[0-9]{4}$/,
            email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
            password: /(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[!@#$%&*()\/\\?]).+/
        },
        messages: {
            first_name: 'Le prénom doit contenir uniquement des lettres et peut inclure des traits d\'union ou des apostrophes.',
            last_name: 'Le nom de famille doit contenir uniquement des lettres et peut inclure des traits d\'union ou des apostrophes.',
            phone_number: 'Le numéro de téléphone doit être au format (123) 456-7890.',
            code: 'Le code doit être un nombre à 4 chiffres.',
            email: 'L\'email doit être une adresse email valide.',
            password: 'Le mot de passe doit contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.'
        },
        validate(field) {
            const value = this.fields[field];
            if (value) {
                const pattern = this.regex[field];
                this.errors[field] = pattern.test(value) ? '' : this.messages[field];
            }
        },
        get hasErrors() {
            return Object.values(this.errors).some(error => error);
        },
        resetForm() {
            for (const key in this.fields) {
                this.fields[key] = '';
            }
            for (const key in this.errors) {
                this.errors[key] = '';
            }
            document.querySelectorAll('input[type=checkbox]').forEach(cb => cb.checked = false);
        }
    };
};

window.fetchRechercheUser = function() {
    return {
        fields: {
            research: '',
            role: '',
            seniority: ''
        },
        async handleSubmit() {
            let url = '/api/user?';
            for (const key in this.fields) {
                url += `${key}=${encodeURIComponent(this.fields[key])}&`;
            }
            url = url.slice(0, -1);

            let response = await fetch(url);
            let data = await response.text();

            const container = document.getElementById('user-cards');
            const allCards = Array.from(container.querySelectorAll('[data-user-id]'));

            let parsed = JSON.parse(data);
            let users = parsed.users || [];

            allCards.forEach(card => {
                card.style.display = 'none';
            });

            users.forEach(user => {
                const card = allCards.find(c => c.getAttribute('data-user-id') == user.id);
                if (card) {
                    card.style.display = 'block';
                    container.appendChild(card);
                }
            });
        }
    };
};

window.fetchUpdateStatus = function(status) {
    return async function (timeoffId) {
        const url = `/api/timeoff/status/${timeoffId}`;
        const options = {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ status })
        };

        try {
            const response = await fetch(url, options);
            if (!response.ok) {
                throw new Error(`Error! status: ${response.status}`);
            }
            const data = await response.json();
            console.log('Timeoff status updated:', data);

            if (data) {
                const statusElement = document.getElementById(`timeoff-status-${timeoffId}`);
                statusElement.textContent = data.status;

                const statusIcons = {
                    'Approuvée': `<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                  </svg>`,
                    'En attente': `<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                  </svg>`,
                    'Expirée': `<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                  </svg>`,
                    'Annulée': `<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                  </svg>`,
                    'Refusée': `<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                  </svg>`
                };

                const statusClasses = {
                    'Approuvée': 'bg-green-100 text-green-800',
                    'En attente': 'bg-yellow-100 text-yellow-800',
                    'Expirée': 'bg-red-100 text-red-800',
                    'Annulée': 'bg-red-100 text-red-800',
                    'Refusée': 'bg-red-100 text-red-800',
                    'default': 'bg-gray-100 text-gray-800'
                };

                const icon = statusIcons[data.status] || '';
                const statusClass = statusClasses[data.status] || statusClasses['default'];

                statusElement.innerHTML = `${icon}<span>${data.status}</span>`;
                statusElement.className = `px-3 py-1 text-xs font-medium rounded-full inline-flex items-center ${statusClass}`;

                const buttonsElement = document.getElementById(`timeoff-buttons-${timeoffId}`);
                buttonsElement.remove();
            } else
                alert('Failed to update timeoff status. Please try again.');

            return data;
        } catch (error) {
            console.error('Error updating timeoff status:', error);
            console.error('Request details:', { url, options });
        }
    };
}


window.schedule_dropdown_change = function(branchId) {
    const currentUrl = window.location.href;
    const urlParts = currentUrl.split('/');
    const weekParam = urlParts[urlParts.length - 1];
    window.location.href = `/schedule/${branchId}/${weekParam}`;
}

window.save_edit_shift = function() {
    const form = document.getElementById('formEdit');

    const formData = new FormData(form);
    console.log(formData);
}

window.fetchFilteredReplacement = async function(status) {
    let url = '/api/replacements';
    if (status) {
        url += '?status=' + status;
    }

    let response = await fetch(url);
    let data = await response.text();
    document.getElementById('replacements-list').innerHTML = data;
}

window.ifUserIsAvailable = async function(userId, branchId, date, startTime, endTime, shiftId=null) {
    let formattedDate = date.replace(/-/g, '');
    let formattedStartTime = startTime.replace(/:/g, '');
    let formattedEndTime = endTime.replace(/:/g, '');
    console.log(userId, branchId, formattedDate, formattedStartTime, formattedEndTime);

    //const url = `/api/shifts/availableEmployees/${branchId}/${formattedDate}/${formattedStartTime}/${formattedEndTime}`;
    const url = `/api/schedule/checkAvailability?id_user=${userId}&id_branch=${branchId}&date=${formattedDate}&startTime=${formattedStartTime}&endTime=${formattedEndTime}&id_shift=${shiftId}`

    try {
        const options = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
        }

        const response = await fetch(url, options);
        const data = await response.json();
        console.log(data);
        if (data['available'] == true) {
            return true;
        } else {
            //console.log(data['reason'])
            document.getElementById('editEmployeeAvailableMessage').classList.remove('hidden');
            document.getElementById('addEmployeeAvailableMessage').classList.remove('hidden');
            let reasonTexts = document.getElementsByClassName('reasonText')
            for(let i=0; i<reasonTexts.length; i++) {
                let element = reasonTexts[i]
                element.innerHTML = "Raison: "
                switch (data['reason']) {
                    case 'userNotFound':
                        element.innerHTML += "L'employé est introuvable.";
                        break;
                    case 'overlap':
                        element.innerHTML += "Il y a un chevauchement avec un quart assigné à cet employé.";
                        break;
                    case 'availability':
                        //Ici, si l y a un problème de disponibilité, on veut afficher la disponibilité de l'employé
                        element.innerHTML += "Le quart ne correspond pas aux disponibilités de l'employé.";

                        let dayOfTheWeek = "";
                        switch (parseInt(data['day'])) {
                            case 0:
                                dayOfTheWeek = "dimanche";
                                break;
                            case 1:
                                dayOfTheWeek = "lundi";
                                break;
                            case 2:
                                dayOfTheWeek = "mardi";
                                break;
                            case 3:
                                dayOfTheWeek = "mercredi";
                                break;
                            case 4:
                                dayOfTheWeek = "jeudi";
                                break;
                            case 5:
                                dayOfTheWeek = "vendredi";
                                break;
                            case 6:
                                dayOfTheWeek = "samedi";
                                break;
                        }

                        if(data['start_time'] && data['end_time']) {
                            element.innerHTML += `<br>L'employé est disponible le ${dayOfTheWeek} de ${data['start_time'].substring(0,5)} à ${data['end_time'].substring(0,5)}.`;
                        }
                        else {
                            element.innerHTML += `<br>L'employé n'est pas disponible le ${dayOfTheWeek}.`;
                        }

                        break;
                    case 'timeOff':
                        element.innerHTML += "Un congé empêche l'employé d'être assigné à ce quart.";
                        break;
                    default:
                        element.innerHTML += "Une erreur inconnue est survenue."
                        break;
                }
            };
            return false;
        }
    } catch (error) {
        console.error('ERREUR:', error);
        return false;
    }
}

window.formAddIsValid = function(){
    const form = document.getElementById('formAdd');
    let isValid = true;

    if (form.querySelector('#addDate').value == ""){
        form.querySelector('#addDate').classList.add('border-red-500');
        isValid = false;
    } else {
        form.querySelector('#addDate').classList.remove('border-red-500');
    }


    if (form.querySelector('#addStart_time').value == ""){
        form.querySelector('#addStart_time').classList.add('border-red-500');
        isValid = false;
    } else {
        form.querySelector('#addStart_time').classList.remove('border-red-500');
    }

    if (form.querySelector('#addEnd_time').value == ""){
        form.querySelector('#addEnd_time').classList.add('border-red-500');
        isValid = false;
    } else {
        form.querySelector('#addEnd_time').classList.remove('border-red-500');
    }

    const startTime = form.querySelector('#addStart_time').value;
    const endTime = form.querySelector('#addEnd_time').value;

    if (startTime && endTime && startTime >= endTime) {
        form.querySelector('#addStart_time').classList.add('border-red-500');
        form.querySelector('#addEnd_time').classList.add('border-red-500');
        isValid = false;
    } else {
        form.querySelector('#addStart_time').classList.remove('border-red-500');
        form.querySelector('#addEnd_time').classList.remove('border-red-500');
    }

    return isValid;
}

window.formEditIsValid = function(){
    const form = document.getElementById('formEdit');
    let isValid = true;

    if (form.querySelector('#editStart_time').value == ""){
        form.querySelector('#editStart_time').classList.add('border-red-500');
        isValid = false;
    } else {
        form.querySelector('#editStart_time').classList.remove('border-red-500');
    }

    if (form.querySelector('#editEnd_time').value == ""){
        form.querySelector('#editEnd_time').classList.add('border-red-500');
        isValid = false;
    } else {
        form.querySelector('#editEnd_time').classList.remove('border-red-500');
    }

    const startTime = form.querySelector('#editStart_time').value;
    const endTime = form.querySelector('#editEnd_time').value;

    if (startTime && endTime && startTime >= endTime) {
        form.querySelector('#editStart_time').classList.add('border-red-500');
        form.querySelector('#editEnd_time').classList.add('border-red-500');
        isValid = false;
    } else {
        form.querySelector('#editStart_time').classList.remove('border-red-500');
        form.querySelector('#editEnd_time').classList.remove('border-red-500');
    }

    return isValid;
}

Alpine.start();
