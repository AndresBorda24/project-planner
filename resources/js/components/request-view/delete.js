import { toastError, toastsSuccess, toastQuestion, url as URL } from "../../extra/utilities.js";

export default () => ({
    async delete() {
        try {
            const _url = `${URL}request/${Alpine.store("currentRequest").id}`;
            const res = await (await fetch(_url, { method: "DELETE" })).json();

            if (res.status == "error") {
                throw new Error(res.message);
            }

            const index = Alpine.store("requests").findIndex(
                (el) => el.id == Alpine.store("currentRequest").id
            );
            
            Alpine.store("currentRequest", Alpine.store("defaultRequest").getDefault() );
            Alpine.store("requests").splice(index, 1);

            toastsSuccess("Solicitud Eliminada!");
        } catch (error) {
            toastError(error.message);
        }
    },
    async remove() {
        const x = await toastQuestion("Seguro?", "red");

        if (x) {
            this.$data.closeBoth();
            await this.delete();
        }

        return;
    },
});
