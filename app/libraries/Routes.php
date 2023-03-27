<?php

class Routes {

    public static function get(array $url) {
        $controller = null;

        switch (strtolower($url[0])) {
            case "mutaties":
                $controller = "Mutations";
                break;
            case "openstaande-mutaties":
                $controller = "OpenMutations";
                break;
            case "mutatiecodes":
                $controller = "MutationCodes";
                break;
            case "categorieen":
                $controller = "Categories";
                break;
            case "rekeningen":
                $controller = "Accounts";
                break;
            case "instellingen":
                $controller = "User";
                break;
            case "overzichten":
                $controller = "Overviews";

                if (isset($url[1])) {
                    switch (strtolower($url[1])) {
                        case "mutaties":
                            $controller = "MutationsOverview";
                            break;
                        case "uitgaven":
                            $controller = "ExpensesOverview";
                            break;
                        case "balans":
                            $controller = "BalanceOverview";
                            break;
                        case "categorieen":
                            $controller = "CategoriesOverview";
                            break;
                        case "budgetten":
                            $controller = "BudgetsOverview";
                            break;
                        case "saldoverloop":
                            $controller = "BalanceHistoryOverview";
                            break;
                    }
                }
                break;
            default:
                $controller = "Mutations";
                break;
        }

        return $controller;
    }

}