import { loader, toastError, url as URL  } from "../../extra/utilities.js";

export default () => ({
    surge: 10,
    areThereMore() {
        return Alpine.store('requests').length > Alpine.store('requestLimit');
    },
    async loadMore() {
        try {
            if ( ! this.areThereMore() || Alpine.store('searchBox') != '') return;

            setTimeout(() => {
                Alpine.store(
                    'requestLimit',
                    Alpine.store('requestLimit') + this.surge
                )
            }, 800);
        } catch(e) {
            toastError( e.message );
        }

        loader.classList.add('d-none');
    }
});
