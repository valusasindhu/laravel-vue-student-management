const state = {
    user: {
        token: sessionStorage.getItem('TOKEN'),
        data: {}
    },
    students: {
        loading: false,
        data: [],
        links: [],
        from: null,
        to: null,
        page: 1,
        limit: null,
        total: null,
    }
};
export default state
