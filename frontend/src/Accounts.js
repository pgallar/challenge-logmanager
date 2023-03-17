import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';
import { Card, Table, Button } from 'react-bootstrap';

const Accounts = () => {
    const [accounts, setAccounts] = useState([]);

    useEffect(() => {
        fetch('http://localhost:8000/api/accounts')
            .then(res => res.json())
            .then(data => setAccounts(data))
            .catch(err => console.log(err));
    }, []);

    const deleteAccount = id => {
        fetch(`http://localhost:8000/api/accounts/${id}`, { method: 'DELETE' })
            .then(() => setAccounts(accounts.filter(account => account.id !== id)))
            .catch(err => console.log(err));
    };

    return (
        <div className="container">
            <Card>
                <Card.Body>
                    <Card.Title>Accounts</Card.Title>
                    <Table striped bordered hover>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Client ID</th>
                                <th>Activated</th>
                                <th>URL Meli callback</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {accounts.map(account => (
                                <tr key={account.id}>
                                    <td>{account.name}</td>
                                    <td>{account.client_id}</td>
                                    <td>{account.activated ? 'true' : 'false'}</td>
                                    <td>{`http://localhost:8000/accounts/callback/${account.short_name}`}</td>
                                    <td>
                                        {!account.activated && <Link to={`https://auth.mercadolivre.com.br/authorization?response_type=code&client_id=${account.client_id}&redirect_uri=http://localhost:8000/accounts/callback/${account.short_name}`} className="mr-2 btn btn-info btn-sm">
                                            Activate
                                        </Link>}
                                        &nbsp;
                                        {account.activated ? <Link to={`/accounts/${account.id}/orders`} className="mr-2 btn btn-primary btn-sm">
                                            Orders
                                        </Link> : null}
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </Table>
                    <Link to="/accounts/new" className="btn btn-primary mb-4">
                        New Account
                    </Link>
                </Card.Body>
            </Card>
        </div>
    );
};

export default Accounts;