<style>
    #modal {
        display: flex;
        flex-grow: 1;
        flex-direction: column;
        justify-content: center;
        height: 100%;
    }

    .iframe {
        flex: 1;
        height: 100%;
        flex-direction: column;
        justify-content: center;
    }

    .loading {
        /* Absolute position */
        left: 0;
        position: absolute;
        top: 0;

        /* Take full size */
        height: 100%;
        width: 100%;

        /* Center */
        align-items: center;
        display: flex;
        justify-content: center;
        flex-direction: column;
    }
</style>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"><?= $judul ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="height: 500px;">
                <div id="modal"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
