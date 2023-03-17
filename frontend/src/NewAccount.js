import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import { useNavigate } from 'react-router-dom';

const NewAccount = () => {
    const [name, setName] = useState('');
    const [clientId, setClientId] = useState('');
    const [clientSecret, setClientSecret] = useState('');
    const [shortName, setShortName] = useState('');
    const navigate = useNavigate();

    const handleSubmit = (event) => {
        event.preventDefault();
        fetch('http://localhost:8000/api/accounts', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ name, client_id: clientId, client_secret: clientSecret, short_name: shortName }),
        })
            .then((response) => response.json())
            .then((data) => {
                console.log(data);
                navigate("/");
            })
            .catch((error) => console.error(error));
    };

    return (
        <div className="container">
            <h2>Create New Account</h2>
            <form onSubmit={handleSubmit}>
                <div className="form-group">
                    <label htmlFor="name">Name</label>
                    <input
                        type="text"
                        className="form-control"
                        id="name"
                        value={name}
                        onChange={(event) => setName(event.target.value)}
                        required
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="client_id">Client ID</label>
                    <input
                        type="text"
                        className="form-control"
                        id="client_id"
                        value={clientId}
                        onChange={(event) => setClientId(event.target.value)}
                        required
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="client_secret">Client Secret</label>
                    <input
                        type="text"
                        className="form-control"
                        id="client_secret"
                        value={clientSecret}
                        onChange={(event) => setClientSecret(event.target.value)}
                        required
                    />
                </div>
                <div className="form-group">
                    <label htmlFor="short_name">Short name</label>
                    <input
                        type="text"
                        className="form-control"
                        id="short_name"
                        value={shortName}
                        onChange={(event) => setShortName(event.target.value)}
                        required
                    />
                </div>
                <button type="submit" className="btn btn-primary mt-4">
                    Create
                </button>
                <Link to="/" className="btn btn-danger mt-4">
                    Cancel
                </Link>
            </form>
        </div>
    );
};

export default NewAccount;