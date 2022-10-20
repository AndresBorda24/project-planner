import { loader, toastError, url as URL  } from "../../extra/utilities.js";

export default () => ({
    showButton() {
        return Alpine.store('canLoadMoreRequests');
    },
    async loadMore() {
        try {
            loader.classList.remove('d-none');

            const ids = encodeURIComponent(Alpine.store("requestsId"));
            const url = `${URL}requests?ids=${ids}`;

            const res = await ( await fetch(url) ).json();

            if (res.status == 'error') {
                throw new Error( res.message );
            }

            Alpine.store("requests").push(...res.requests);
            Alpine.store("canLoadMoreRequests", res.canFetchMore);
        } catch(e) {
            toastError( e.message );
        }

        loader.classList.add('d-none');
    }
});