export default () => ({
    show: 0,
    disable() {
        if (!Object.prototype.hasOwnProperty.call(Alpine.store('currentRequest'), 'id')) {
            return true;
        }
        return false;
    },
    changeShow(_) {
        if (_ === this.show) {
            this.show = 0;
            return;
        };
        this.show = _;
    },
    closeBoth() {
        this.show = 0;
    }
});
