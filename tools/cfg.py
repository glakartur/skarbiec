#!/usr/bin/env python
# -*- coding: utf-8 -*-

class Cfg:
    def __init__(self):
        self.fileName = ".skarbiec"
        self.transactionsFolder = "Transakcje"
        self.dataFolder = "data"
        self.transactionsFileName = "transactions.json"

    @property
    def transactionsFile(self):
        return f"{self.dataFolder}/{self.transactionsFileName}"

if __name__ == "__main__":
    pass