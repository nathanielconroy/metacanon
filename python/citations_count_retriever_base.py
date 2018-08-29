from abc import ABC, abstractmethod


class CitationsCountRetrieverBase(ABC):
    @abstractmethod
    def get_num_citations(self, author_first_name, author_last_name, title):
        pass
