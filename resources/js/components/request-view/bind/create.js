import { url as URL, toastError, _modal, loader, _fetch } from "../../../extra/utilities.js";

export default () => ({
    state: { delegate_id: '0' },
    project: {},
    setUp( detail ) {
        if( Object.prototype.hasOwnProperty.call(detail, 'project') ) {
            this.project = detail.project;
        }

        const current = Alpine.store('currentRequest');
        this.state.title = current.subject.substring(0, 50);
        this.state.description = current.subject.substring(50);
    },
    hasProject() {
        if( Object.prototype.hasOwnProperty.call(this.project, 'id') ) {
            return true;
        }
        return false;
    },
    goBack(step = null) {
        let xStep = step;

        if (xStep === null) {
            xStep = this.hasProject() ? 2 : 1;
        }

        this.$dispatch('change-step', xStep);
        this.state = { delegate_id: '0' };
        this.project =  {};
    },
    getUri() {
        if (this.hasProject()) {
            return `${URL}project/${this.project.id}/tasks`;
        }
        return `${URL}project`;
    },
    /**
     * Guarda xD
     */
    async save() {
        const _url = this.getUri();
        const body = this.state;
        try {
            loader.classList.remove('d-none');

            const res = await _fetch(_url, "POST", body);

            if (res.status == "error") {
                toastError(res.message);
                return;
            }

            if ( this.hasProject() ) {
                this.$dispatch('new-project-info', this.project);
            } else {
                this.$dispatch('new-project-info', res.project);
            }

            //  this.goBack(4);
        } catch (error) {
            toastError(error.message);
        }
        loader.classList.add('d-none');
    }
});
