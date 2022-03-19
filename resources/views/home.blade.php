@extends('layouts.main')

@section('content')
<div class="row">
    <div class="col-12">
        <div id="myModal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajouter un évenement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAdd" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="titre" class="form-label">Titre d'évenement: </label>
                                <input type="text" class="form-control" id="titre" placeholder="Entrez le titre de l'évenement" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" rows="3" placeholder="Entrez la description de l'évenement" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="time_start" class="form-label">Date de début</label>
                                <input name="start" class="form-control" id="debut" type="datetime-local" required />
                            </div>
                            <div class="mb-3">
                                <label for="time_start" class="form-label">Date de fin</label>
                                <input name="fin" class="form-control" id="fin" type="datetime-local" required />
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success" id="addEvent">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="showEven" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Information de l'évenement : </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5>Titre de l'évenement: </h5>
                        <p class="titre"></p>
                        <h5>Description de l'évenement: </h5>
                        <p class="description"></p>
                        <h5>Date de début : </h5>
                        <p class="debut"></p>
                        <h5>Date de fin : </h5>
                        <p class="fin"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="button" class="btn btn-success" id="edit">Éditer</button>
                        <button type="button" class="btn btn-danger" id="delete">Supprimer</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="editModal" class="modal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier un évenement</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formAdd" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="titre" class="form-label">Titre d'évenement: </label>
                                <input type="text" class="form-control titre" id="updateTitre" placeholder="Evenement ... " value="">
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control description" id="updateDescription" rows="3" placeholder="Description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="time_start" class="form-label">Date de début</label>
                                <input name="start" class="form-control debut" id="updateDebut" type="datetime-local" required />
                            </div>
                            <div class="mb-3">
                                <label for="time_start" class="form-label">Date de fin</label>
                                <input name="fin" class="form-control fin" id="updateFin" type="datetime-local" required />
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success" id="updatEvent">Modifier</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="calendar" class="mt-5"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const addEvt = async (evt) => {
        try {
            const res = await fetch('/calendar', {
                method: 'POST',
                body: JSON.stringify(evt),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            const data = await res.json()
            if (data.errors) {
                Swal.fire(
                    'Error ',
                    'Il faut remplir tous les champs',
                    'error'
                )
            }
            if (data) {
                Swal.fire(
                    'Evenement Ajouté!',
                    'Vous avez ajouter un nouveau évenement :  ' + evt.title,
                    'success'
                )
            }
            location.reload();
        } catch (err) {
            console.log(err)
        }
    }

    const getDate = (src) => {
        const start = src.start.getFullYear() + '-' + (src.start.getMonth() + 1) + '-' + src.start.getDate() + ' ' + src.start.getHours() + ':' + src.start.getMinutes() + ':' + src.start.getSeconds()
        const end = src.end.getFullYear() + '-' + (src.end.getMonth() + 1) + '-' + src.end.getDate() + ' ' + src.end.getHours() + ':' + src.end.getMinutes() + ':' + src.end.getSeconds()
        return {
            start: start,
            end: end
        }
    }

    const updateEvt = async (evt, id) => {
        try {
            const res = await fetch('/calendar/' + id, {
                method: 'PUT',
                body: JSON.stringify(evt),
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            const data = await res.json()
            if (data.errors) {
                Swal.fire(
                    'Error ',
                    'Vous devez remplir tous les champs',
                    'error'
                )
            } else {
                Swal.fire(
                    'Evenement Modifié!',
                    'Vous avez ajouter un nouveau évenement :  ' + evt.titre,
                    'success',
                )
                location.reload();
            }
        } catch (err) {
            console.log(err)
        }
    }

    const showEvt = async (id, type) => {
        try {
            const res = await fetch('/calendar/' + id, {
                method: 'GET',
            })
            const data = await res.json()
            if (data.errors) {
                console.log(data.errors);
            } else {
                if (type === "edit") {
                    var dat = new Date(data.start)
                    var form = dat.getFullYear() + '-' + dat.getMonth() + '-' + dat.getDay() + 'T' + dat.getHours() + ':' + dat.getMinutes()
                    $('#editModal').modal('show')
                    $('.titre').val(data.title)
                    $('.description').val(data.description)
                    $('.debut').val(data.start)
                    $('.fin').val(form)
                } else {
                    $('#showEven').modal('show')
                    $('.titre').text(data.title)
                    $('.description').text(data.description)
                    $('.debut').text(data.start)
                    $('.fin').text(data.end)
                }
            }
        } catch (err) {
            console.log(err)
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Ajouter un évenement
        $('#addEvent').click((e) => {
            e.preventDefault();
            $(this).html('Sending..');
            // Récuperation des valeurs des inputs
            const event = {
                title: document.getElementById('titre').value,
                description: document.getElementById('description').value,
                start: document.getElementById('debut').value,
                end: document.getElementById('fin').value
            }
            event.start < event.end ? addEvt(event) : Swal.fire(
                'Error ',
                'Entrez des dates valides',
                'error'
            )
        });

        // initialisation du calendrier
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            googleCalendarApiKey: 'AIzaSyAQ7RduPezh1BkzUqzUcfZekq9n084xJ5Q',
            eventSources: [{
                    // Récuperer les evenemements de la base de donnée
                    url: '/calendar',
                },
                {
                    // Récuperer les evenemements de Google Calendar
                    googleCalendarId: 'hamzasaoulajane9@gmail.com',
                    color: 'green',
                }
            ],
            locale: 'fr',
            timeZone: 'local',
            editable: true,
            droppable: true,
            height: 800,
            selectable: true,
            // Header du calendrier
            headerToolbar: {
                left: 'prevYear,prev,next,nextYear today addEventButton',
                center: 'title',
                end: "today prev,next",
                right: 'timeGridDay,timeGridWeek,dayGridMonth,listWeek'
            },
            // Personnaliser button Ajouter un évenement
            customButtons: {
                addEventButton: {
                    text: 'Ajouter un évenement',
                    click: () => {
                        $('#myModal').modal('show')
                    }
                }
            },
            select: async (selectinfo) => {
                // Ajouter un evenement par selectionner sur le calendrier
                const {
                    value: formValues
                } = await Swal.fire({
                    title: 'Ajouter un événement',
                    html: '<label class="swal2-label">Titre</label>' +
                        '<input type="text" id="swal-input1" class="swal2-input">' +
                        '<label class="swal2-label"> Description</label>' +
                        '<input  id="swal-input2" class="swal2-input">',
                    focusConfirm: true,
                    showCancelButton: true,
                    inputValidator: (formValues) => {
                        if (!formValues) {
                            alert('Vous devez écrire quelque chose!')
                        }
                    },
                    preConfirm: () => {
                        // Validation des inputs
                        if (document.getElementById('swal-input1').value && document.getElementById('swal-input2').value) {
                            return [
                                document.getElementById('swal-input1').value,
                                document.getElementById('swal-input2').value
                            ]
                        } else {
                            // Message d'erreur si un input est vide
                            Swal.showValidationMessage('Il faut remplir tous les champs')
                        }
                    }
                })
                if (formValues) {
                    const {
                        start,
                        end
                    } = getDate(selectinfo)
                    const data = {
                        title: formValues[0],
                        description: formValues[1],
                        start: start,
                        end: end
                    }
                    data.start < data.end ? addEvt(data) : null
                }
            },
            navLinks: true,

            // Afficher information d'un évenement
            eventClick: async (info) => {
                showEvt(info.event.id, "show");
                // Supprimer un évenement
                $('#delete').click(async () => {
                    try {
                        const res = await fetch('/calendar/' + info.event.id, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        })
                        const data = await res.json()
                        if (!data.errors) {
                            $('#showEven').modal('hide');
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Evenement supprimé',
                                showConfirmButton: false,
                                timer: 5
                            })
                        }
                    } catch (err) {
                        console.log(err)
                    }
                    location.reload();
                });

                $('#edit').click(async () => {
                    // Recuperer/afficher les datas
                    showEvt(info.event.id, "edit");
                });

                // Modifier un evenement
                $("#updatEvent").click(async (e) => {
                    e.preventDefault();
                    $(this).html('Sending..');
                    const data = {
                        title: document.getElementById('updateTitre').value,
                        description: document.getElementById('updateDescription').value,
                        start: document.getElementById('updateDebut').value,
                        end: document.getElementById('updateFin').value
                    }
                    data.start < data.end ? updateEvt(data, info.event.id) : Swal.fire(
                        'Error ',
                        'Entrez des dates valides',
                        'error'
                    )
                })
            },

            // Modifier un evenement avec drop Event
            eventDrop: async (info) => {
                const {
                    start,
                    end
                } = getDate(info)
                const data = {
                    title: info.event.title,
                    description: info.event.extendedProps.description,
                    start: start,
                    end: end
                }
                data.start < data.end ? updateEvt(data, info.event.id) : null
            },

            // Modifier un evenement avec Resize Event
            eventResize: (eventResizeInfo) => {
                const {
                    start,
                    end
                } = getDate(eventResizeInfo)
                const data = {
                    title: eventResizeInfo.event.title,
                    description: eventResizeInfo.event.extendedProps.description,
                    start: start,
                    end: end
                }
                data.start < data.end ? updateEvt(data, eventResizeInfo.event.id) : null
            }
        });
        calendar.render();
    });
</script>
@endsection