export function setUser(state, user) {
    state.user.data = user
}

export function setToken(state, token) {
    state.user.token = token;
    if (token) {
        sessionStorage.setItem('TOKEN', token);
    } else {
        sessionStorage.removeItem('TOKEN');
    }
}

export function setStudents(state, [loading, response = null]) {
    if (response) {
        state.students = {
            ...state.students,
            data: response.data,
            links: response.meta?.links,
            page: response.meta.current_page,
            limit: response.meta.per_page,
            from: response.meta.from,
            to: response.meta.to,
            total: response.meta.total,
        }
    }
    state.students.loading = loading;

}
