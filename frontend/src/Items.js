import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import { Table, Button } from 'react-bootstrap';
import { useNavigate } from 'react-router-dom';

const Items = () => {
    const { orderId } = useParams();
    const [items, setItems] = useState([]);
    const navigate = useNavigate();

    useEffect(() => {
        const fetchItems = async () => {
            const response = await fetch(`http://localhost:8000/api/orders/${orderId}/items`);
            const data = await response.json();
            setItems(data);
        };
        fetchItems();
    }, [orderId]);

    return (
        <div className="container mt-5">
            <h3>Items of Order {orderId}</h3>
            <Table striped bordered hover>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Currency</th>
                    </tr>
                </thead>
                <tbody>
                    {items.map((item) => (
                        <tr key={item.id}>
                            <td>{item.item_id}</td>
                            <td>{item.title}</td>
                            <td>{item.quantity}</td>
                            <td>{item.unit_price}</td>
                            <td>{item.currency_id}</td>
                        </tr>
                    ))}
                </tbody>
            </Table>

            <Button variant="danger" size="sm" onClick={() => navigate(-1)}>
                Go back
            </Button>
        </div>
    );
};

export default Items;