// src/auth.js
import axios from 'axios';

const checkLoggedIn = async () => {
  try {
    await axios.get('/user'); // Replace with your protected API endpoint
    return true; // User is logged in
  } catch (error) {
    return false; // User is not logged in
  }
};

export default checkLoggedIn;
