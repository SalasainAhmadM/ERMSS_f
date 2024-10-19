<style>

    /* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

/* Modal content */
.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
    border-radius: 5px;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
    position: relative;
}

/* Close button */
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
}

/* Modal buttons */
.modal-buttons {
    text-align: center;
}

.modal-buttons button {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
}

.modal-buttons button:hover {
    background-color: #0056b3;
}

</style>

<!-- Error Modal -->
<div id="errorModal" class="modal" style="display: <?php echo $message ? 'block' : 'none'; ?>">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p><?php echo $message; ?></p>
    </div>
</div>
