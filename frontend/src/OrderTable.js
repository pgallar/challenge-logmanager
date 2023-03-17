import React, { useState, useEffect } from 'react';
import { Table, Button } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import { useParams, useNavigate } from "react-router-dom";

const OrderTable = () => {
    const [orders, setOrders] = useState([]);
    const { accountId } = useParams();
    const navigate = useNavigate();

    useEffect(() => {
        fetch(`http://localhost:8000/api/accounts/${accountId}/orders`)
            .then(res => res.json())
            .then(data => setOrders(data))
            .catch(err => console.log(err));
    }, []);

    return (
        <div className="container mt-4">
            <h4>Order List</h4>
            <Table striped bordered hover>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    {orders.map(order => (
                        <tr key={order.id}>
                            <td>{order.order_id}</td>
                            <td>{order.buyer_id}</td>
                            <td>{order.status}</td>
                            <td>{order.total_amount}</td>
                            <td>
                                <Link to={`/orders/${order.id}/items`} className="mr-2 btn btn-primary btn-sm">
                                    Items
                                </Link>
                            </td>
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

export default OrderTable;