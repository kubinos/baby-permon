import axios from 'axios';

export function getEnums () {
  return axios.get('/api/config/enums');
}

// levels

export function getLevels () {
  return axios.get('/api/config/level');
}

export function updateLevels (levels) {
  return axios.put('/api/config/level', levels);
}

// sounds

export function getSounds () {
  return axios.get('/api/sounds');
}

export function getSound (soundId) {
  return axios.get(`/api/sounds/${soundId}`);
}

export function createSound (sound) {
  return axios.post('/api/sounds', sound);
}

export function updateSound (sound) {
  return axios.put(`/api/sounds/${sound.id}`, sound);
}

export function deleteSound (soundId) {
  return axios.delete(`/api/sounds/${soundId}`);
}

// station

export function getStations () {
  return axios.get('/api/stations');
}

export function getStation (stationId) {
  return axios.get(`/api/stations/${stationId}`);
}

export function createStation (station) {
  return axios.post('/api/stations', station);
}

export function updateStation (station) {
  return axios.put(`/api/stations/${station.id}`, station);
}

export function deleteStation (stationId) {
  return axios.delete(`/api/stations/${stationId}`);
}

// task

export function getTasks () {
  return axios.get('/api/tasks');
}

export function getTask (taskId) {
  return axios.get(`/api/tasks/${taskId}`);
}

export function createTask (task) {
  return axios.post('/api/tasks', task);
}

export function updateTask (task) {
  return axios.put(`/api/tasks/${task.id}`, task);
}

export function deleteTask (taskId) {
  return axios.delete(`/api/tasks/${taskId}`);
}

// game

export function getGames () {
  return axios.get('/api/games');
}

export function getGamesActive () {
  return axios.get('/api/games/active');
}

export function createGame (game) {
  return axios.post('/api/games', game);
}

export function deleteGame (gameId) {
  return axios.delete(`/api/games/${gameId}`);
}

export function getGameLogs (gameId) {
  return axios.get(`/api/games/logs/${gameId}`);
}
