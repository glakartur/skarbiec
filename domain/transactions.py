#!/usr/bin/env python
# -*- coding: utf-8 -*-

import os
import json
from tools.cfg import Cfg
from domain.transaction import Transaction

class Transactions:
    def __init__(self):
        self._transactions = []
        self.config = Cfg()

    def append(self, transaction):
        self._transactions.append(transaction)

    def save(self):
        with open(self.config.transactionsFile, 'w', encoding = 'utf-8') as f:
            json.dump(self._transactions, f, default=lambda x: x.__dict__, indent=1, ensure_ascii=False)

if __name__ == "__main__":
    pass