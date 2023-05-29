import axiosClient from "../axios";

export function getCurrentUser({commit}, data) {    
    return axiosClient.get('/user', data)
        .then(({data}) => {
            commit('setUser', data);
            return data;
        })
}

export function login({commit}, data) {    
    return axiosClient.post('/login', data)
        .then(({data}) => {
            commit('setUser', data.user);
            commit('setToken', data.token);
            return data;
        })
        .catch(function (error) {
            console.log(error)
        })
}

export function logout({commit}) {
    return axiosClient.post('/logout')
        .then((response) => {
            commit('setToken', null)

            return response;
        })
}

export function getStudents({commit, state}, {url = null, search = '', per_page, sort_field, sort_direction} = {}) {
    commit('setStudents', [true])
    url = url || '/students'
    const params = {
        per_page: state.students.limit,
    }
    return axiosClient.get(url, {
        params: {
            ...params,
            search, per_page, sort_field, sort_direction
        }
    })
        .then((response) => {
            commit('setStudents', [false, response.data])
        })
        .catch(() => {
            commit('setStudents', [false])
        })
}

export function createStudent({commit}, student) {
    if (student.image instanceof File) {
        const form = new FormData();
        form.append('title', student.title);
        form.append('image', student.image);
        form.append('description', student.description || '');
        form.append('published', student.published ? 1 : 0);
        form.append('price', student.price);
        student = form;
    }
    return axiosClient.post('/students', student)
}

export function updateStudent({commit}, student) {
    const id = student.id
    if (student.image instanceof File) {
        const form = new FormData();
        form.append('id', student.id);
        form.append('title', student.title);
        form.append('image', student.image);
        form.append('description', student.description || '');
        form.append('published', student.published ? 1 : 0);
        form.append('price', student.price);
        form.append('_method', 'PUT');
        student = form;
    } else {
        student._method = 'PUT'
    }
    return axiosClient.post(`/students/${id}`, student)
}

export function deleteStudent({commit}, id) {
    return axiosClient.delete(`/students/${id}`);
}

export function getStudent({commit}, id) {
    return axiosClient.get(`/students/${id}`)
}

