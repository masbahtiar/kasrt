<style>
    #flashModal {
        display: flex;
        flex-grow: 1;
        flex-direction: column;
        justify-content: start;
    }
</style>

<div class="modal fade" id="myFlashModal" tabindex="-1" role="dialog" aria-labelledby="myFlashModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulFlashMessage"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px; overflow: scroll;">
                <div id="flashModal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
