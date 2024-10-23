
// Add
function openEventTypeModal() {
    Swal.fire({
        title: 'Add Event Type',
        input: 'text',
        inputLabel: 'Enter Event Type',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        customClass: {
            popup: 'larger-swal' 
        }
    }).then((result) => {
        if (result.isConfirmed) {
            addEventType(result.value);
        }
    });
}

function addEventType(eventTypeName) {
    fetch('add_event_type.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ event_type_name: eventTypeName })
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              Swal.fire({
                  title: 'Success!',
                  text: 'Event Type has been added: ' + eventTypeName,
                  icon: 'success',
                  customClass: {
                      popup: 'larger-swal' 
                  }
                }).then(() => {
                  window.location.reload();
              });
          } else {
              Swal.fire({
                  title: 'Error!',
                  text: 'Event Type has already been added.',
                  icon: 'error',
                  customClass: {
                      popup: 'larger-swal' 
                  }
                }).then(() => {
                  window.location.reload();
              });
          }
      });
}

function openEventModeModal() {
    Swal.fire({
        title: 'Add Event Mode',
        input: 'text',
        inputLabel: 'Enter Event Mode',
        showCancelButton: true,
        confirmButtonText: 'Submit',
        customClass: {
            popup: 'larger-swal' 
        }
    }).then((result) => {
        if (result.isConfirmed) {
            addEventMode(result.value);
        }
    });
}

function addEventMode(eventModeName) {
    fetch('add_event_mode.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ event_mode_name: eventModeName })
    }).then(response => response.json())
      .then(data => {
          if (data.success) {
              Swal.fire({
                  title: 'Success!',
                  text: 'Event Mode has been added: ' + eventModeName,
                  icon: 'success',
                  customClass: {
                      popup: 'larger-swal' 
                  }
                }).then(() => {
                  window.location.reload();
              });
          } else {
              Swal.fire({
                  title: 'Error!',
                  text: 'Event Mode has already been added.',
                  icon: 'error',
                  customClass: {
                      popup: 'larger-swal' 
                  }
                }).then(() => {
                  window.location.reload();
              });
          }
      });
}
// Edit
function editEventType(eventTypeId, eventTypeName) {
    const originalEventTypes = [
        "Training Sessions",
        "Specialized Seminars",
        "Cluster-specific gathering",
        "General Assembly",
        "Workshop"
    ];

    if (originalEventTypes.includes(eventTypeName)) {
        Swal.fire({
            title: 'Cannot Edit',
            text: `cannot edit origin event type: ${eventTypeName}`,
            icon: 'warning',
            customClass: {
                popup: 'larger-swal'
            }
        });
    } else {
        Swal.fire({
            title: 'Edit Event Type',
            input: 'text',
            inputValue: eventTypeName,
            inputLabel: 'Enter New Event Type',
            showCancelButton: true,
            confirmButtonText: 'Update',
            customClass: {
                popup: 'larger-swal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('edit_event_type.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        event_type_id: eventTypeId,
                        event_type_name: result.value
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Event Type has been updated.',
                            icon: 'success',
                            customClass: {
                                popup: 'larger-swal'
                            }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            customClass: {
                                popup: 'larger-swal'
                            }
                        });
                    }
                });
            }
        });
    }
}
function editEventMode(eventModeId, eventModeName) {
    const originalEventModes = [
        "Face-to-Face",
        "Online",
        "Hybrid"
    ];

    if (originalEventModes.includes(eventModeName)) {
        Swal.fire({
            title: 'Cannot Edit',
            text: `cannot edit origin event mode: ${eventModeName}`,
            icon: 'warning',
            customClass: {
                popup: 'larger-swal'
            }
        });
    } else {
        Swal.fire({
            title: 'Edit Event Mode',
            input: 'text',
            inputValue: eventModeName,
            inputLabel: 'Enter New Event Mode',
            showCancelButton: true,
            confirmButtonText: 'Update',
            customClass: {
                popup: 'larger-swal'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('edit_event_mode.php', {
                    method: 'POST',
                    headers: {
                        'Content-Mode': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        event_mode_id: eventModeId,
                        event_mode_name: result.value
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Event Mode has been updated.',
                            icon: 'success',
                            customClass: {
                                popup: 'larger-swal'
                            }
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: data.message,
                            icon: 'error',
                            customClass: {
                                popup: 'larger-swal'
                            }
                        });
                    }
                });
            }
        });
    }
}

function confirmDeleteEventType(eventTypeId) {
Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to undo this action!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel',
    customClass: {
        popup: 'larger-swal'
    }
}).then((result) => {
    if (result.isConfirmed) {
        fetch('delete_event_type.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                event_type_id: eventTypeId
            })
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  Swal.fire({
                      title: 'Deleted!',
                      text: 'Event type has been deleted.',
                      icon: 'success',
                      customClass: {
                          popup: 'larger-swal'
                      }
                  }).then(() => {
                      window.location.reload();
                  });
              } else {
                  Swal.fire({
                      title: 'Error!',
                      text: data.message,
                      icon: 'error',
                      customClass: {
                          popup: 'larger-swal'
                      }
                  });
              }
          });
    }
});
}

function confirmDeleteEventMode(eventModeId) {
Swal.fire({
    title: 'Are you sure?',
    text: "You won't be able to undo this action!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Yes, delete it!',
    cancelButtonText: 'Cancel',
    customClass: {
        popup: 'larger-swal'
    }
}).then((result) => {
    if (result.isConfirmed) {
        fetch('delete_event_mode.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams({
                event_mode_id: eventModeId
            })
        }).then(response => response.json())
          .then(data => {
              if (data.success) {
                  Swal.fire({
                      title: 'Deleted!',
                      text: 'Event mode has been deleted.',
                      icon: 'success',
                      customClass: {
                          popup: 'larger-swal'
                      }
                  }).then(() => {
                      window.location.reload();
                  });
              } else {
                  Swal.fire({
                      title: 'Error!',
                      text: data.message,
                      icon: 'error',
                      customClass: {
                          popup: 'larger-swal'
                      }
                  });
              }
          });
    }
});
}
