export default () => ({
    show: false,
    step: 1,
    changeToStep3() {
        this.step = 3;
        this.$dispatch('bind-request', {});
    },
    close() {
        this.show = false;
        this.step = 1;
    }
});
