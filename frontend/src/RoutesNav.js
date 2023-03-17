import React from "react";
import { Route, Routes, BrowserRouter } from "react-router-dom";

import Accounts from "./Accounts";
import Items from "./Items";
import NewAccount from "./NewAccount";
import OrderTable from "./OrderTable";

const RoutesNav = () => {
    return (
        <BrowserRouter>
            <Routes>
                <Route element={<Accounts />} path="/" />
                <Route element={<NewAccount />} path="/accounts/new" />
                <Route element={<OrderTable />} path="/accounts/:accountId/orders" />
                <Route element={<Items />} path="/orders/:orderId/items" />
            </Routes>
        </BrowserRouter>
    )
}

export default RoutesNav;